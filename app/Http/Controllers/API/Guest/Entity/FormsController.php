<?php

namespace App\Http\Controllers\API\Guest\Entity;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\ProductEntityLocationPrice;

class FormsController extends BaseController
{
    public function getFormWizardsByProductId($productId){

        $productEntityLocationPrices = ProductEntityLocationPrice::where('product_id', $productId)
            ->with('formWizardProducts.formWizard')->get();

        $formWizards = [];
        foreach ($productEntityLocationPrices as $key => $productEntityLocationPrice) {
            array_push($formWizards, $productEntityLocationPrice->formWizardProducts);
        }
        return $this->sendResponse([
            'formWizards' => $formWizards,
        ],
            'Successfully retrieved form wizards by product id');
    }
}
