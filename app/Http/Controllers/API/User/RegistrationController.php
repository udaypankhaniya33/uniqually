<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use App\Jobs\SendVerificationEmail;
use App\Notifications\VerifyCustomerRegistration;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $incomingData['email'] = request('email');
        $incomingData['first_name'] = encrypt(request('first_name'));
        $incomingData['last_name'] = encrypt(request('last_name'));
        $incomingData['activation_code'] = Str::random(32);
        $incomingData['is_social_auth'] = false;
        $incomingData['created_at'] = Carbon::now();
        $incomingData['updated_at'] = Carbon::now();

        try {
             $user = new User($incomingData);
             $user->save();
             $user->name = decrypt($user->name);

             dispatch(new SendVerificationEmail($user))->delay(Carbon::now()->addSeconds(2));

             $user->activation_code = null;
             $user->token = $user->createToken('vManageTax')-> accessToken;
             $user->name = decrypt($user->name);

             return $this->sendResponse($user,
                'Thank you for registering with UniaAlly. You will get your account activation code in your email');
        } catch (\Exception $ex) {
            return $this->sendError('Something went wrong while sending activation code',
                ['error' => $ex->getMessage()], 422);
        }
    }

    // Check authentication status
    public function checkAuth(){
        $user = Auth::user();
        $user->name = decrypt($user->name);
        return $this->sendResponse($user,
            'Authentication successful');
    }

}
