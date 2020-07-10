<?php

namespace App\Http\Controllers\API\Guest\Entity;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\ProductEntityLocationPrice;

class ProductsController extends BaseController
{
    public function index(){

        $products = ProductEntityLocationPrice::with('product', 'location', 'entity', 'productFeatures', 'productAddonPrices', 'productFormationSteps')->get();
    
        return $this->sendResponse([
            'products' => $products,
        ],
            'Successfully retrieved all products with associated data');
    }
}
