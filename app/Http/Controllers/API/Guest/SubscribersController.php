<?php

namespace App\Http\Controllers\API\Guest;

use App\Http\Controllers\API\BaseController;
use App\Subscriber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Location\Facades\Location;
use function MongoDB\BSON\toJSON;

class SubscribersController extends BaseController
{

    /**
     * Subscribe an email
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function store(){
        $validator = Validator::make(request()->all(), [
            'email' => 'required|unique:subscribers|email',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please provide valid data', ['error'=>$validator->errors()], 422);
        }
        $location = Location::get(request()->ip());
        $incomingData = request()->all();
        $incomingData['created_at'] = Carbon::now();
        $incomingData['updated_at'] = Carbon::now();
        $incomingData['location'] = $location ? json_encode( $location->toArray() ): null;
        $incomingData['user_agent'] = request()->header('user-agent') ? request()->header('user-agent') : null ;
        try {
            Subscriber::create($incomingData);
            return $this->sendResponse([], 'Thanks for completing the survey. You will receive discount code through email');
        } catch (\Exception $ex) {
            return $this->sendError('Something went wrong while uploading',
                ['error' => $ex->getMessage()], 422);
        }
    }

}
