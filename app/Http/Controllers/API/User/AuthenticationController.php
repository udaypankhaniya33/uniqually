<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use App\Jobs\SendTwoFactorAuthentication;
use App\LoginAttempt;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthenticationController extends BaseController
{

    /**
     * Check authentication status
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function checkAuth(){
        $user = Auth::user();
        $loginAttempt = LoginAttempt::where('user_id', $user->id)
            ->orderBy('id', 'desc')->first();
        $resUser = [
            'name' => decrypt($user->name),
            'email_verified_at' => $user->email_verified_at,
            'is_social_auth' => $user->is_social_auth,
            '2fa_verified' => $loginAttempt !== null && $loginAttempt->is_verified ? true : false
        ];
        return $this->sendResponse([
            'user' => $resUser
        ],
            'Authentication successful');
    }

    /**
     * Authenticate a user manually
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function authenticate() {
        $validator = Validator::make(request()->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please provide valid data', ['error'=>$validator->errors()], 422);
        }
        if(User::isUserExists(request('email')) && !User::isSocialMediaUser(request('email'))){
            if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
                $authorizedUser = Auth::user();
                $token = $authorizedUser->createToken('vManageTax')-> accessToken;
                $resUser = [
                    'name' => decrypt($authorizedUser->name),
                    'email_verified_at' => $authorizedUser->email_verified_at,
                    'is_social_auth' => $authorizedUser->is_social_auth,
                    '2fa_verified' => false
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
                    dispatch(new SendTwoFactorAuthentication($authorizedUser, $twoFactorCode))->delay(Carbon::now()
                        ->addSeconds(2));
                    $message = 'Two factor authentication code was sent to '.$authorizedUser->email;
                }else{
                    $message = 'Your account is still inactive';
                }
                return $this->sendResponse([
                    'user' => $resUser,
                    'token' => $token
                ],$message);
            }else{
                return $this->sendError('Unauthorized', [], 401);
            }
        }else{
            return $this->sendError('Unauthorized', [], 401);
        }
    }

}
