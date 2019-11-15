<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use App\Notifications\VerifyCustomerRegistration;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegistrationController extends BaseController
{

    /**
     * Subscribe an email
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function store(){
        $validator = Validator::make(request()->all(), [
            'email' => 'required|string|unique:users|email|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please provide valid data', ['error'=>$validator->errors()], 422);
        }

        $incomingData['type'] = config('constances.user_types')['CUSTOMER'];
        $incomingData['name'] = encrypt(request('first_name').' '.request('last_name'));
        $incomingData['password'] = Hash::make(request('password'));
        $incomingData['email'] = encrypt(request('email'));
        $incomingData['first_name'] = encrypt(request('first_name'));
        $incomingData['last_name'] = encrypt(request('last_name'));
        $incomingData['activation_code'] = Str::random(32);
        $incomingData['created_at'] = Carbon::now();
        $incomingData['updated_at'] = Carbon::now();

        try {
             $user = new User($incomingData);
             $user->save();
             $user->email = decrypt($user->email);
             $user->name = decrypt($user->name);
             $user->notify(new VerifyCustomerRegistration($user));
             return $this->sendResponse([],
                'Thank you for registering with uniqally. You will get your account activation code in your email');
        } catch (\Exception $ex) {
            return $this->sendError('Something went wrong while sending activation code',
                ['error' => $ex->getMessage()], 422);
        }
    }

}
