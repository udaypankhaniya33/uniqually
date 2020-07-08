<?php

namespace App\Http\Controllers\API\Guest\Entity;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\EntityType;

class EntityTypesController extends BaseController
{
    public function index(){
        $entityTypes = EntityType::all();
        return $this->sendResponse([
            'entityTypes' => $entityTypes,
        ],
            'Successfully retrieved all entity type details');
    }
}
