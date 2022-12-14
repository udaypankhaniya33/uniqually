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
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|min:8|max:15'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please provide valid data', ['error'=>$validator->errors()], 422);
        }
        $incomingData['type'] = config('constances.user_types')['CUSTOMER'];
        $incomingData['name'] = encrypt(request('first_name').' '.request('last_name'));
        $incomingData['password'] = Hash::make(request('password'));
        $incomingData['email'] = request('email');
        $incomingData['phone'] = request('phone');
        $incomingData['first_name'] = encrypt(request('first_name'));
        $incomingData['last_name'] = encrypt(request('last_name'));
        $incomingData['activation_code'] = Str::random(10);
        $incomingData['is_social_auth'] = false;
        $incomingData['created_at'] = Carbon::now();
        $incomingData['updated_at'] = Carbon::now();
        if(request()->has('is_entity')){
            $incomingData['is_entity'] = true;
        }
        try {
             $user = new User($incomingData);
             $user->save();
             dispatch(new SendVerificationEmail($user))->delay(Carbon::now()->addSeconds(2));
             $user->token = $user->createToken('vManageTax')-> accessToken;
             $resUser = [
                 'name' => decrypt($user->name),
                 'email_verified_at' => $user->email_verified_at,
                 'is_social_auth' => $user->is_social_auth,
                 'two_factor_verified' => false,
                 'email' => $user->email
             ];
             return $this->sendResponse([
                 'user' => $resUser,
                 'token' => $user->token,
                 'incomingData' => $incomingData
             ],
                'Account activation code was sent to '.$user->email);
        } catch (\Exception $ex) {
            return $this->sendError('Something went wrong while sending activation code',
                ['error' => $ex->getMessage()], 422);
        }
    }

}
