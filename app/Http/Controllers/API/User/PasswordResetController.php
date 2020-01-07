<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use App\Jobs\SendPasswordResetLink;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PasswordResetController extends BaseController
{

    /**
     * Send password reset link
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function sendResetPasswordLink() {
        if(request()->has('email')){
            if(User::isUserExists(request('email'))){
                $user = User::where('email', request('email'))->first();
                $resetToken = Str::random(15);
                DB::table('password_resets')->insert([
                    'email' => request('email'),
                    'token' => $resetToken,
                    'created_at' => Carbon::now()
                ]);
                dispatch(new SendPasswordResetLink($user, $resetToken))->delay(Carbon::now()->addSeconds(2));
                return $this->sendResponse([
                ], 'Reset password link was sent to '.$user->email);
            }else{
                return $this->sendError('User not found', ['error'=> [
                    'email' => 'We could not found any user with given email address'
                ]], 422);
            }
        }else{
            return $this->sendError('Please provide valid data', ['error'=> [
                'email' => 'Email is required'
            ]], 422);
        }
    }

}
