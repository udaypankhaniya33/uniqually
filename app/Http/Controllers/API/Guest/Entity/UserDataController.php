<?php

namespace App\Http\Controllers\API\Guest\Entity;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\EntityFormationUserData;

class UserDataController extends BaseController
{
    public function store(){

        if(request()->has('user_id', 'form_wizard_product_id')){
            $data = request()->all();
            unset($data->user_id);
            unset($data->form_wizard_product_id);
            $data = encrypt(json_encode($data));

            $entityFormationUserData = new EntityFormationUserData([
                'user_id' => request('user_id'),
                'form_wizard_product_id' => request('form_wizard_product_id'),
                'data' => $data
            ]);
            $entityFormationUserData->save();

            return $this->sendResponse([
                'entityFormationUserData' => $entityFormationUserData,
            ],
                'Successfully stored User Data');
        }else{
            return $this->sendError('Please provide valid data', [
                'user_id' => 'User id is required',
                'form_wizard_product_id' => 'Form Wizard Product id required'
            ], 422);
        }
        
    }
}
