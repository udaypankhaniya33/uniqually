<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use App\Jobs\SendTwoFactorAuthentication;
use App\LoginAttempt;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $user->name = decrypt($user->name);
        return $this->sendResponse($user,
            'Authentication successful');
    }

    /**
     * Authenticate a user manually
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function authenticate() {
        if(request()->has('email', 'password')){
            if(User::isUserExists(request('email'))){
                $user = User::where('email', request('email'))->first();
                if($user->password !== null && $user->is_social_auth == false){
                    if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
                        $user = Auth::user();
                        $user->token = $user->createToken('vManageTax')-> accessToken;
                        $user->name = decrypt($user->name);
                        if($user->email_verified_at === null){
                            return $this->sendResponse($user, 'Your account is still inactive ');
                        }else{
                            $twoFactorCode = Str::random(6);
                            $loginAttempt = new LoginAttempt([
                                'user_id' => $user->id,
                                'authentication_code' => $twoFactorCode,
                                'is_verified' => false,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ]);
                            $loginAttempt->save();
                            $user->login_verified = false;
                            dispatch(new SendTwoFactorAuthentication($user, $twoFactorCode))->delay(Carbon::now()->addSeconds(2));
                            return $this->sendResponse($user, 'Two factor authentication code has been sent to ');
                        }
                    }else{
                        return $this->sendError('Unauthorized', [], 401);
                    }
                }else{
                    return $this->sendError('Social media user', [], 422);
                }
            }
        }else{
            return $this->sendError('Please provide valid data', ['error'=> [
                'email' => 'Email is required',
                'password' => 'Password is required'
            ]], 422);
        }
    }

}
