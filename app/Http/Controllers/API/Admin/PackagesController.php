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
     * Get all packages
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return $this->sendResponse(
            Package::all()
            , 'Successfully retrieved all packages');
    }

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
            'discounted_price' => 'numeric',
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

    /**
     * Update packages
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function update(){
        $validator = Validator::make(request()->all(), [
            'title' => 'required|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric',
            'discounted_price' => 'numeric',
            'package_category_id' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please provide valid data', ['error'=>$validator->errors()], 422);
        }

        $incomingData = [
            'title' => request('title'),
            'description' => request('description'),
            'price' => request('price'),
            'discounted_price' => request('discounted_price'),
            'package_category_id' => request('package_category_id'),
            'updated_at' => Carbon::now()
        ];

        $package = Package::where('id', request('id'))->update($incomingData);
        return $this->sendResponse([], 'Package updated successfully.');
    }

    /**
     * Delete packages
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function delete(){
        $package = Package::find(request('id'));
        if($package){
            $package->delete();
            return $this->sendResponse([], 'Package deleted successfully.');
        }else{
            return $this->sendError('Could not find package record for given ID', [], 404);
        }
    }

}
