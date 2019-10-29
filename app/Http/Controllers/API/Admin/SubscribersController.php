<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Subscriber;
use Illuminate\Http\Request;
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
        $subscribers = Subscriber::all();
        foreach ($subscribers as $index => $subscriber){
            if($subscriber->location != null){
                $subscriber->location = json_decode($subscriber->location);
            }
            array_push($result, $subscriber);
        }
        return $this->sendResponse($result, 'Successfully retrieved all coupon codes');
    }
}
