<?php

namespace App\Http\Controllers\API\User;

use App\Charity;
use App\CharityAssociation;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CharityAssociationController extends BaseController
{

    /**
     * Return all charity details
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $charities = Charity::all();
        $userSelectedCharity = CharityAssociation::where('user_id', Auth::id())
            ->join('charities', 'charity_associations.charity_id', '=', 'charities.id')->latest('charity_associations.id')->first();
        return $this->sendResponse([
            'charities' => $charities,
            'selectedCharity' => $userSelectedCharity
        ],
            'Successfully retrieved all charities with details');
    }


    /**
     * Associate user with charity organization
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function associate() {
       if(request()->has('charity_id')){
           $charityAssociation = new CharityAssociation([
               'user_id' => Auth::id(),
               'charity_id' => request('charity_id')
           ]);
           $charityAssociation->save();
           return $this->sendResponse([],
               'Successfully associated');
       }else{
           return $this->sendError('Please provide valid data', [
               'error' => [
                   'charity_id' => 'Charity Id is required',
               ]
           ], 422);
       }
    }

}
