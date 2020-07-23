<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Support\Facades\Auth;

class PasswordChangeController extends BaseController
{
    /**
     * Change Password
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function changePassword() {
        $validator = Validator::make(request()->all(), [
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6',
            'new_confirm_password' => 'required|same:new_password'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please provide valid data', ['error'=>$validator->errors()], 422);
        }
        $user = User::find(Auth::id());
        if(Hash::check(request('old_password'), $user->password)){
            $user->update(['password'=> Hash::make(request('new_password'))]);
            return $this->sendResponse([
                'user' => $user
            ],
                'Your password has been changed successfully');
        }else{
            return $this->sendError('Please provide valid data', ['error'=>[
                'password_mismatch' => 'Old password is invalid'
            ]], 422);
        }

    }
}
