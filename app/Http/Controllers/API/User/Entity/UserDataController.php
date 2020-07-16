<?php

namespace App\Http\Controllers\API\User\Entity;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\EntityFormationUserData;
use Illuminate\Support\Facades\Auth;

class UserDataController extends BaseController
{
    public function store(){

        if(request()->has('form_wizard_product_id')){
            $data = request()->all();
            unset($data->form_wizard_product_id);
            $data = encrypt(json_encode($data));

            $entityFormationUserData = new EntityFormationUserData([
                'user_id' => Auth::id(),
                'form_wizard_product_id' => (int)request('form_wizard_product_id'),
                'data' => $data
            ]);
            $entityFormationUserData->save();
            

            return $this->sendResponse([
                'entityFormationUserData' => $entityFormationUserData,
            ],
                'Successfully stored User Data');
        }else{
            return $this->sendError('Please provide valid data', [
                'form_wizard_product_id' => 'Form Wizard Product id required'
            ], 422);
        }
        
    }
}
