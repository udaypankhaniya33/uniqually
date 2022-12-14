<?php

namespace App\Http\Controllers\API\User\Entity;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ProductAddonPrice;
use App\ProductEntityLocationPrice;
use App\Order;
use App\OrderAddon;

class OrdersController extends BaseController
{
    public function store(){
        
        $currentUser = Auth::user();
        $orderAddons = request()->has('order_addons') ? request('order_addons') : null;

        if(request()->has('product_entity_location_price_id', 'payment_occurrence')){
            return $this->order(
                request('product_entity_location_price_id'),
                request('payment_occurrence'),
                $currentUser,
                $orderAddons);
        }else{
            return $this->sendError('Please provide valid data', [
                'error' => [
                    'product_entity_location_price_id' => 'Product entity location price id is required to initiate order',
                    'payment_occurrence' => 'Payment Occurrence id is required'
                ]
            ], 422);
        }
    }

    public function order($productEntityLocationPriceId, $paymentOccurrence, $currentUser, $orderAddons){
       
        $netValue = 0;
        $selectedProductEntityLocationPrice = ProductEntityLocationPrice::with('product')->find($productEntityLocationPriceId);

        if($paymentOccurrence === config('constances.payment_occurrences')['IS_ANNUAL']){
            $netValue = ($selectedProductEntityLocationPrice->price * 12) * ($selectedProductEntityLocationPrice->annual_discount / 100);
        }else{
            $netValue = $selectedProductEntityLocationPrice->price;
        }

        $order = new Order([
            'order_created_by' => $currentUser->id,
            'order_creator_location' => null,
            'net_value' => $netValue,
            'base_package_id' => null,
            'custom_ind' => strtoupper(uniqid()),
            'order_status' => null,
            'is_entity_order' => true,
            'product_entity_location_price_id' => $productEntityLocationPriceId,
            'payment_occurrence' => $paymentOccurrence
        ]);
        $order->save();

        if($orderAddons !== null){
            $orderAddons = json_decode($orderAddons);
            $orderAddonsNetValue = 0;

            foreach ($orderAddons as $key => $orderAddon) {

                $productAddonPrice = ProductAddonPrice::find($orderAddon->id);

                if($productAddonPrice){
                    if($orderAddon->payment_occurrence === config('constances.payment_occurrences')['IS_ANNUAL']){
                        $orderAddonsNetValue = $orderAddonsNetValue + ($productAddonPrice->price * 12) * ($productAddonPrice->annual_discount / 100);
                    }else{
                        $orderAddonsNetValue = $orderAddonsNetValue + $productAddonPrice->price;
                    }
    
                    $createOrderAddon = new OrderAddon([
                        'order_id' => $order->id,
                        'package_addon_id' => null,
                        'product_addon_price_id' => $orderAddon->id,
                        'quantity' => $orderAddon->quantity,
                        'payment_occurrence' => $orderAddon->payment_occurrence
                    ]);
                    $createOrderAddon->save();
                }
            }

            $order->net_value = $order->net_value + $orderAddonsNetValue;
            $order->save();
            
        }

        return $this->sendResponse([
            'order' => $order,
            'orderAddons' => $orderAddons
        ], 'Order has been submitted successfully!');
    }
    
}
