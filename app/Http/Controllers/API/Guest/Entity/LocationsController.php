<?php

namespace App\Http\Controllers\API\Guest\Entity;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Location;

class LocationsController extends BaseController
{
    public function index(){
        $locations = Location::all();
        return $this->sendResponse([
            'locations' => $locations,
        ],
            'Successfully retrieved all location details');
    }
}
