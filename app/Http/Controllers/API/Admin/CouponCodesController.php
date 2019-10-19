<?php

namespace App\Http\Controllers\API\Admin;

use App\CouponCode;
use App\Http\Controllers\API\BaseController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponCodesController extends BaseController
{

    /**
     * Add new coupon codes
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function store(){
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string',
            'discount' => 'required|numeric',
            'is_enabled' => 'boolean'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please provide valid data', ['error'=>$validator->errors()], 422);
        }
        $incomingData = request()->all();
        $incomingData['created_at'] = Carbon::now();
        $incomingData['updated_at'] = Carbon::now();
        CouponCode::create($incomingData);
        return $this->sendResponse([], 'Successfully created the coupon code');
    }

    /**
     * Coupon code coupon codes
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function update(){
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string',
            'discount' => 'required|numeric',
            'is_enabled' => 'required|boolean'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please provide valid data', ['error'=>$validator->errors()], 422);
        }

        $incomingData = [
            'name' => request('name'),
            'discount' => request('discount'),
            'is_enabled' => request('is_enabled')
        ];

        $couponCode = CouponCode::where('id', request('id'))->update($incomingData);
        return $this->sendResponse([], 'Coupon code updated successfully.');
    }
}
