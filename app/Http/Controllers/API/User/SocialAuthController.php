<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use App\Jobs\SendTwoFactorAuthentication;
use App\Jobs\SendVerificationEmail;
use App\LoginAttempt;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use function GuzzleHttp\Promise\is_settled;

class SocialAuthController extends BaseController
{
    /**
     * Sign In / Sign up with social media
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function auth() {
        $validator = Validator::make(request()->all(), [
            'token' => 'required|string|max:255',
            'provider' => 'required|string|max:100'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please provide valid data', ['error'=>$validator->errors()], 422);
        }
        $user = Socialite::driver(request('provider'))->userFromToken(request('token'));
        if(isset($user->email) && $user->email!==null){
            if(User::isUserExists($user->email)){
                User::where('email', $user->email)->update([
                    'remember_token' => $user->token,
                    'is_social_auth' =>true,
                    'updated_at' => Carbon::now()
                ]);
                $authorizedUser = User::where('email', $user->email)->first();
                $token = $authorizedUser->createToken('vManageTax')-> accessToken;
                $resUser = [
                    'name' => decrypt($authorizedUser->name),
                    'email_verified_at' => $authorizedUser->email_verified_at,
                    'is_social_auth' => $authorizedUser->is_social_auth,
                    'two_factor_verified' => false
                ];
                if($authorizedUser->email_verified_at !== null){
                    $twoFactorCode = Str::random(6);
                    $loginAttempt = new LoginAttempt([
                        'user_id' => $authorizedUser->id,
                        'authentication_code' => $twoFactorCode,
                        'is_verified' => false,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                    $loginAttempt->save();
                    dispatch(new SendTwoFactorAuthentication($authorizedUser, $twoFactorCode))->delay(Carbon::now()->addSeconds(2));
                    $message = 'Two factor authentication code was sent to '.$authorizedUser->email;
                }else{
                    $message = 'Your account is still inactive';
                }
                return $this->sendResponse([
                    'user' => $resUser,
                    'token' => $token
                ],$message);
            }
            else{
                $initiatedUser = new User([
                    'email' => $user->email,
                    'name' => encrypt($user->name),
                    'type' =>  config('constances.user_types')['CUSTOMER'],
                    'remember_token' => $user->token,
                    'is_social_auth' =>true,
                    'activation_code' => Str::random(10),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
                $initiatedUser->save();
                $token = $initiatedUser->createToken('vManageTax')-> accessToken;
                dispatch(new SendVerificationEmail($initiatedUser))->delay(Carbon::now()->addSeconds(2));
                $resUser = [
                    'name' => decrypt($initiatedUser->name),
                    'email_verified_at' => $initiatedUser->email_verified_at,
                    'is_social_auth' => $initiatedUser->is_social_auth,
                    'two_factor_verified' => false
                ];
                return $this->sendResponse([
                    'user' => $resUser,
                    'token' => $token
                ], 'An activation email was sent to '.$initiatedUser->email);
            }
        }else{
            return $this->sendError('Invalid social media account', [
                'error'=> [
                    'email' => 'The social media account you have chosen does not contain an email'
                ]
            ], 422);
        }
    }


}
