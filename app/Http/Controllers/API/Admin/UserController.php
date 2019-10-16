<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{

    /**
     * Authenticates admin users(only)
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            //Sending error if non admin user tried to access admin panel
            if($user->type !== config('constances.user_types')['ADMIN']){
                return $this->sendError('Only admin users can access admin panel!', [], '401');
            }
            $success['token'] =  $user->createToken('vManageTax')-> accessToken;
            return $this->sendResponse($success, 'Successfully Authenticated');
        }else{
            return $this->sendError('Unauthorized', [], 401);
        }
    }
}
