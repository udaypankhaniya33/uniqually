<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\PackageAddon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackageAddonsController extends BaseController
{

    /**
     * Get all package addons
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return $this->sendResponse(
            PackageAddon::all()
            , 'Successfully retrieved all package addons');
    }

    /**
     * Add new package addons
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function store(){
        $validator = Validator::make(request()->all(), [
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'discounted_price' => 'numeric',
            'package_id' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please provide valid data', ['error'=>$validator->errors()], 422);
        }
        $incomingData = request()->all();
        $incomingData['created_at'] = Carbon::now();
        $incomingData['updated_at'] = Carbon::now();
        PackageAddon::create($incomingData);
        return $this->sendResponse([], 'Successfully created the package addon');
    }

    /**
     * Update package addons
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function update(){
        $validator = Validator::make(request()->all(), [
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'discounted_price' => 'numeric',
            'package_id' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please provide valid data', ['error'=>$validator->errors()], 422);
        }

        $incomingData = [
            'title' => request('title'),
            'price' => request('price'),
            'discounted_price' => request('price'),
            'package_id' => request('price'),
            'updated_at' => Carbon::now()
        ];

        $packageAddon = PackageAddon::where('id', request('id'))->update($incomingData);
        return $this->sendResponse([], 'Package addon updated successfully.');
    }

    /**
     * Delete package addons
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function delete(){
        $packageAddons = PackageAddon::find(request('id'));
        if($packageAddons){
            $packageAddons->delete();
            return $this->sendResponse([], 'Package addon deleted successfully.');
        }else{
            return $this->sendError('Could not find package addon record for given ID', [], 404);
        }
    }

}
