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
     * Add new package addons
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function store(){
        $validator = Validator::make(request()->all(), [
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
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
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please provide valid data', ['error'=>$validator->errors()], 422);
        }

        $incomingData = [
            'title' => request('title'),
            'price' => request('price'),
            'updated_at' => Carbon::now()
        ];

        $packageAddon = PackageAddon::where('id', request('id'))->update($incomingData);
        return $this->sendResponse([], 'Package addon updated successfully.');
    }

}
