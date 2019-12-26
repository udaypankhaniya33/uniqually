<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use App\Jobs\SendTwoFactorAuthentication;
use App\LoginAttempt;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TwoFactorAuthController extends BaseController
{

    /**
     * Resend 2FA code
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function resendTwoFactorAuth() {
        $authUser = Auth::user();
        $lastLoginAttempt = LoginAttempt::where('user_id', $authUser->id)
            ->where('is_verified', false)
            ->orderBy('id', 'desc')->first();
        if($lastLoginAttempt !== null){
            dispatch(new SendTwoFactorAuthentication($authUser, $lastLoginAttempt->authentication_code))
                ->delay(Carbon::now()->addSeconds(2));
            return $this->sendResponse([],
                'Two factor code resent to '.$authUser->email);
        }else{
            return $this->sendError('Invalid request', [
                'error' => [
                    'token' => 'We could not find any login attempt with your account'
                ]
            ], 401);
        }
    }

    /**
     * Verify 2FA code
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function verifyTwoFactorCode() {
        if(request()->has('two_factor_code')){
            $authUser = Auth::user();
            $loginAttempt = LoginAttempt::where('user_id', $authUser->id)
                ->where('is_verified', false)
                ->orderBy('id', 'desc')->first();
            if($loginAttempt !== null && $loginAttempt->authentication_code === request('two_factor_code')){
                LoginAttempt::where('id', $loginAttempt->id)
                    ->update([
                        'is_verified' => true,
                        'updated_at' => Carbon::now()
                    ]);
                $resUser = [
                    'name' => decrypt($authUser->name),
                    'email_verified_at' => $authUser->email_verified_at,
                    'is_social_auth' => $authUser->is_social_auth,
                    'two_factor_verified' => true,
                    'email' => $authUser->email
                ];
                $token = $authUser->createToken('vManageTax')-> accessToken;
                return $this->sendResponse([
                    'user' => $resUser,
                    'token' => $token
                ], 'Successfully authenticated');
            }else{
                return $this->sendError('Please provide valid data', ['error'=> [
                    'two_factor_code' => 'Two factor code is invalid'
                ]], 422);
            }
        }else{
            return $this->sendError('Please provide valid data', ['error'=> [
                'two_factor_code' => 'Two factor code is required'
            ]], 422);
        }
    }

}
