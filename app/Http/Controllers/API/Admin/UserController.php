<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
            if($user->type !== config('constances.user_types')['ADMIN'] ||
                $user->type !== config('constances.user_types')['ACCOUNT MANAGER'] ||
                $user->type !== config('constances.user_types')['TAX PREPARER']){
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
        return $this->sendResponse($success, 'Successfully retrieved admin users');
    }

    /**
     * Add new admin users
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function store(){
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please provide valid data', ['error'=>$validator->errors()], 422);
        }
        $incomingData = request()->all();
        $incomingData['type'] = config('constances.user_types')['ADMIN'];
        $incomingData['password'] = Hash::make($incomingData['password']);
        $incomingData['created_at'] = Carbon::now();
        $incomingData['updated_at'] = Carbon::now();
        User::create($incomingData);
        return $this->sendResponse([], 'Successfully created the new admin user');
    }

}
