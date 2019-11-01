<?php

namespace App\Http\Controllers\API\Admin;

use App\CouponCode;
use App\Http\Controllers\API\BaseController;
use App\Mail\CouponCodeMailable;
use App\Subscriber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stevebauman\Location\Facades\Location;

class SubscribersController extends BaseController
{

    /**
     * Get all subscribers with all relevant data
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $result = [];
        $subscribers = Subscriber::join('coupon_codes', 'subscribers.coupon_code_id', 'coupon_codes.id')
        ->select('subscribers.*', 'coupon_codes.name as coupon_code')->get();
        foreach ($subscribers as $index => $subscriber){
            if($subscriber->location != null){
                $subscriber->location = json_decode($subscriber->location);
            }
            array_push($result, $subscriber);
        }
        return $this->sendResponse($result, 'Successfully retrieved all coupon codes');
    }

    /**
     * Resend coupon code
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function resendCoupon(){
        if(request()->has('subscriber_id')){
            $subscriber = Subscriber::find(request('subscriber_id'));
            $couponCodeChosen = CouponCode::find($subscriber->coupon_code_id);
            $email = new CouponCodeMailable($couponCodeChosen->name, $couponCodeChosen->discount);
            Mail::to($subscriber->email)->send($email);
            if (!Mail::failures()) {
                $subscriber->is_code_sent = true;
                $subscriber->updated_at = Carbon::now();
                $subscriber->save();
                return $this->sendResponse([], 'Coupon code has been resent successfully');
            }else{
                return $this->sendError('Something went wrong while resending email', [], 500);
            }
        }else{
            return $this->sendError('Subscriber ID is required', [], 422);
        }
    }
}
