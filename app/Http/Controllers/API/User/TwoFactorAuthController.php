<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use App\Jobs\SendTwoFactorAuthentication;
use App\LoginAttempt;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $lastLoginAttempt = LoginAttempt::where('user_id', Auth::id())->orderBy('id', 'desc')->first();
        if($lastLoginAttempt !== null){
            dispatch(new SendTwoFactorAuthentication($authUser, $lastLoginAttempt->authentication_code))->delay(Carbon::now()
                ->addSeconds(2));
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

}
