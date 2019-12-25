<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends BaseController
{

    // Check authentication status
    public function checkAuth(){
        $user = Auth::user();
        $user->name = decrypt($user->name);
        return $this->sendResponse($user,
            'Authentication successful');
    }

}
