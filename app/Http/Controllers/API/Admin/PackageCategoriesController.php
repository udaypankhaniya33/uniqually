<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\PackageCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackageCategoriesController extends BaseController
{

    /**
     * Add new package categories
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function store(){
        $validator = Validator::make(request()->all(), [
            'title' => 'required|string',
            'description_heading' => 'required|string',
            'description' => 'required|string'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please provide valid data', ['error'=>$validator->errors()], 422);
        }
        $incomingData = request()->all();
        $incomingData['created_at'] = Carbon::now();
        $incomingData['updated_at'] = Carbon::now();
        PackageCategory::create($incomingData);
        return $this->sendResponse([], 'Successfully created the package category');
    }
}
