<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use App\Jobs\SendOrderConfirmation;
use App\Order;
use App\OrderAddon;
use App\Package;
use App\PackageAddon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;

class OrdersController extends BaseController
{

    /**
     * Submit order
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function store() {
        $currentUser = Auth::user();
        $location = Location::get(request()->ip());
        $orderSummery = [];
        $orderValue = 0;
        if(request()->has('base_package_id')){
            $selectedPackage = Package::with('packageCategory')->find(request('base_package_id'));
            $orderValue = $orderValue + (float)$selectedPackage->discounted_price;
            $createdOrder = new Order([
                'order_created_by' => $currentUser->id,
                'order_creator_location'=> null,
                'net_value' => $orderValue,
                'base_package_id' => $selectedPackage->id
            ]);
            $createdOrder->save();
            array_push($orderSummery, [
                'item' =>  $selectedPackage->packageCategory->title.' - '.$selectedPackage->title,
                'quantity' => 1,
                'costPerItem' =>  (float)$selectedPackage->discounted_price,
                'totalCost' => (float)$selectedPackage->discounted_price
            ]);
            if(request()->has('package_addons')){
                $selectedPackageAddonsWithQty = explode(',', request('package_addons'));
                foreach ($selectedPackageAddonsWithQty as $index => $addon){
                    $addonAndQuantity = explode(':', $addon);
                    $selectedAddonId = $addonAndQuantity[0];
                    $selectedAddonQuantity = $addonAndQuantity[1];
                    $selectedAddon = PackageAddon::find($selectedAddonId);
                    if($selectedAddon !== null){
                        $cost = (float)$selectedAddon->discounted_price * $selectedAddonQuantity;
                        $orderValue = $orderValue + $cost;
                        $orderAddonCreated = new OrderAddon([
                            'order_id' => $createdOrder->id,
                            'package_addon_id' => $selectedAddon->id,
                            'quantity' => $selectedAddonQuantity
                        ]);
                        $orderAddonCreated->save();
                        array_push($orderSummery, [
                            'item' =>  $selectedAddon->title,
                            'quantity' => (int)$selectedAddonQuantity,
                            'costPerItem' =>  (float)$selectedAddon->discounted_price,
                            'totalCost' => $cost
                        ]);
                    }
                }
            }
            Order::where('id', $createdOrder->id)->update([
               'net_value' => $orderValue
            ]);
            dispatch(new SendOrderConfirmation(
                $createdOrder->id,
                Carbon::now(),
                $orderSummery,
                $currentUser->email,
                $orderValue
            ));
            return $this->sendResponse([
                'summery' => $orderSummery,
                'total_cost' => $orderValue,
                'order_id' => $createdOrder->id
            ], 'Order has been submitted successfully!');

        }else{
            return $this->sendError('Please provide valid data', [
                'error'=> [
                    'base_package_id' => 'Base package is required to initiate order'
                ]
            ], 422);
        }
    }

}
