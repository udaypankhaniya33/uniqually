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
                $authenticatedUser = User::where('activation_code', request('activation_code'))->first();
                if($authenticatedUser->email_verified_at === null){
                    User::where('activation_code', request('activation_code'))->update([
                        'email_verified_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                    $authenticatedUser = User::where('activation_code', request('activation_code'))->first();
                    $token = $authenticatedUser->createToken('vManageTax')-> accessToken;
                    $resUser = [
                        'name' => decrypt($authenticatedUser->name),
                        'email_verified_at' => $authenticatedUser->email_verified_at,
                        'is_social_auth' => $authenticatedUser->is_social_auth,
                        'two_factor_verified' => $authenticatedUser->two_factor_verified,
                        'email' => $authenticatedUser->email
                    ];
                    return $this->sendResponse([
                        'user' => $resUser,
                        'token' => $token
                    ],
                        'Account has been activated');
                }else{
                    return $this->sendError('Already activated', ['error'=> [
                        'token' => 'Activation code has been expired'
                    ]], 422);
                }
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
