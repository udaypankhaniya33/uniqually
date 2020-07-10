<?php

namespace App\Http\Controllers\API\Guest\Entity;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\ProductEntityLocationPrice;

class FormationStepsController extends BaseController
{
    public function getFormationStepsByEntityAndLocation($entityId, $locationId){

        $productEntityLocationPrices = ProductEntityLocationPrice::where('entity_id', $entityId)
            ->where('location_id', $locationId)
            ->with('productFormationSteps.formationStep')->get();

        $formationSteps = [];
        foreach ($productEntityLocationPrices as $key => $productEntityLocationPrice) {
            array_push($formationSteps, $productEntityLocationPrice->productFormationSteps);
        }
        return $this->sendResponse([
            'formationSteps' => $formationSteps,
        ],
            'Successfully retrieved formation steps by entity id and location id');
    }
}
