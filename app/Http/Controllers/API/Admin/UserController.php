<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\User;
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
            $userDetails = [
                'name' => $user->name,
                'id' => $user->id,
                'email' => $user->email
            ];
            $success['token'] =  $user->createToken('vManageTax')-> accessToken;
            $success['user'] =  $userDetails;
            return $this->sendResponse($success, 'Successfully Authenticated');
        }else{
            return $this->sendError('Unauthorized', [], 401);
        }
    }

    /**
     * Return all admin users
     * -----------------------------------------------------------------------------------------------------------------
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $adminUsers = User::where('type', config('constances.user_types')['ADMIN'])->get();
        $success['adminUsers'] = $adminUsers;
        return $this->sendResponse($success, 'Successfully Authenticated');
    }
}
