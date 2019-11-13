<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Package;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackagesController extends BaseController
{

    /**
     * Add new packages
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function store(){
        $validator = Validator::make(request()->all(), [
            'title' => 'required|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric',
            'package_category_id' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please provide valid data', ['error'=>$validator->errors()], 422);
        }
        $incomingData = request()->all();
        $incomingData['created_at'] = Carbon::now();
        $incomingData['updated_at'] = Carbon::now();
        Package::create($incomingData);
        return $this->sendResponse([], 'Successfully created the package');
    }
}
