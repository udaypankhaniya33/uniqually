<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

        if(request()->has('token', 'provider')){

            $user = Socialite::driver(request('provider'))->userFromToken(request('token'));

            if(User::isUserExists($user->email)){
                User::where('email', $user->email)->update([
                   'remember_token' => $user->token,
                   'is_social_auth' =>true,
                   'updated_at' => Carbon::now()
                ]);
            }else{
                $newUser = new User([
                    'email' => $user->email,
                    'name' => encrypt($user->name),
                    'type' =>  config('constances.user_types')['CUSTOMER'],
                    'remember_token' => $user->token,
                    'is_social_auth' =>true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
                $newUser->save();
            }
            $createdUser = User::where('email', $user->email)->first();
            $createdUser->name = decrypt($createdUser->name);
            $createdUser->token =  $createdUser->createToken('vManageTax')-> accessToken;
            return $this->sendResponse($createdUser,
                'Successfully authenticated!');

        }else{
            return $this->sendError('Please provide valid data', ['error'=> [
                'token' => 'Social auth token is required'
            ]], 422);
        }
    }


}
