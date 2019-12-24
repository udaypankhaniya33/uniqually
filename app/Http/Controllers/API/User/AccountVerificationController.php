<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use App\Jobs\SendVerificationEmail;
use App\User;
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

    /**
     * Resend verification code
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function verifyCode() {
        if(request()->has('activation_code')){
            $foundCount = User::where('activation_code', request('activation_code'))->count();
            if($foundCount > 0){
                User::where('activation_code', request('activation_code'))->update([
                    'email_verified_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
                return $this->sendResponse([],
                    'Account has been activated');
            }else{
                return $this->sendError('Please provide valid data', ['error'=> [
                    'token' => 'Activation code is invalid'
                ]], 422);
            }
        }else{
            return $this->sendError('Please provide valid data', ['error'=> [
                'activation_code' => 'Activation code is required'
            ]], 422);
        }
    }

}
