<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\PackageAttribute;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackageAttributesController extends BaseController
{

    /**
     * Get all package attributes
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return $this->sendResponse(
            PackageAttribute::all()
            , 'Successfully retrieved all package attributes');
    }

    /**
     * Add new package attributes
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function store(){
        $validator = Validator::make(request()->all(), [
            'package_id' => 'required|integer',
            'attribute' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please provide valid data', ['error'=>$validator->errors()], 422);
        }
        $incomingData = request()->all();
        $incomingData['created_at'] = Carbon::now();
        $incomingData['updated_at'] = Carbon::now();
        PackageAttribute::create($incomingData);
        return $this->sendResponse([], 'Successfully created the package attribute');
    }

    /**
     * Update package attributes
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function update(){
        $validator = Validator::make(request()->all(), [
            'package_id' => 'required|integer',
            'attribute' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please provide valid data', ['error'=>$validator->errors()], 422);
        }

        $incomingData = [
            'package_id' => request('package_id'),
            'attribute' => request('attribute'),
            'updated_at' => Carbon::now()
        ];

        $packageAttribute = PackageAttribute::where('id', request('id'))->update($incomingData);
        return $this->sendResponse([], 'Package attribute updated successfully.');
    }

}
