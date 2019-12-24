<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use App\Jobs\SendVerificationEmail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountVerificationController extends BaseController
{

    /**
     * Resend verification code
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function resendVerification() {
        $user = Auth::user();
        dispatch(new SendVerificationEmail($user))->delay(Carbon::now()->addSeconds(2));
        return $this->sendResponse([],
            'Activation email re sent');
    }

}
