<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use App\Jobs\SendPasswordChangeConfirmation;
use App\Jobs\SendPasswordResetLink;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
                if($user->is_social_auth){
                    return $this->sendError('Social authenticated user', ['error'=> [
                        'email' => 'This email address is associated with a unqally account using third-party login. Please login using Google or Facebook'
                    ]], 422);
                }
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

    /**
     * Verify password reset link
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function verifyPassword() {
        $validator = Validator::make(request()->all(), [
            'reset_token' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please provide valid data', ['error'=>$validator->errors()], 422);
        }
        $resetRecord = DB::table('password_resets')->where('token', request('reset_token'))
            ->orderBy('created_at', 'desc')->first();
        if($resetRecord !== null){
           if(Carbon::now()->diffInHours($resetRecord->created_at) > 2){
               return $this->sendError('Please provide valid data', ['error'=>[
                   'reset_token' => 'Password reset link is Expired'
               ]], 422);
           }
           User::where('email', $resetRecord->email)->update([
               'password' => Hash::make(request('password'))
           ]);
            $resUser = User::where('email',$resetRecord->email )->first();
            $resUser->name = decrypt($resUser->name);
            $token = $resUser->createToken('vManageTax')-> accessToken;
            DB::table('password_resets')->where('token', request('reset_token'))->delete();
            $ip = request()->ip();
            dispatch(new SendPasswordChangeConfirmation($resUser, $ip))->delay(Carbon::now()->addSeconds(2));
            return $this->sendResponse([
                'user' => $resUser,
                'token' => $token
            ],
                'Your password has been reset successfully');
        }else{
            return $this->sendError('Please provide valid data', ['error'=>[
                'reset_token' => 'Password reset link is Invalid'
            ]], 422);
        }

    }

}
