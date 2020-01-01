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
        //$location = Location::get(request()->ip());
        $orderSummery = [];
        $orderValue = 0;
        $quantity = 1;
        $itemEach = 0;
        if(request()->has('base_package_id')){
            $selectedPackage = Package::with('packageCategory')->find(request('base_package_id'));
            $itemEach =  $selectedPackage->discounted_price;
            if(request()->has(['is_annual', 'expense']) && $selectedPackage->packageCategory->title === 'Bookkeeping'){
                $discount = (float)$selectedPackage->discounted_price * 20 / 100;
                $selectedPackage->discounted_price = request('is_annual') === 'true' ?
                    (float)$selectedPackage->discounted_price - $discount : $selectedPackage->discounted_price;
                $selectedPackage->discounted_price = round($selectedPackage->discounted_price);
                switch (request('expense')){
                    case 20000:
                        $selectedPackage->discounted_price = request('is_annual') === 'true' ?
                            (float)$selectedPackage->discounted_price + 40 :
                            (float)$selectedPackage->discounted_price + 50;
                        break;
                    case 30000:
                        $selectedPackage->discounted_price = request('is_annual') === 'true' ?
                            (float)$selectedPackage->discounted_price + (40*2) :
                            (float)$selectedPackage->discounted_price + (50*2);
                        break;
                    case 40000:
                        $selectedPackage->discounted_price = request('is_annual') === 'true' ?
                            (float)$selectedPackage->discounted_price + (40*3) :
                            (float)$selectedPackage->discounted_price + (50*3);
                        break;
                    case 50000:
                        $selectedPackage->discounted_price = request('is_annual') === 'true' ?
                            (float)$selectedPackage->discounted_price + (40*4) :
                            (float)$selectedPackage->discounted_price + (50*4);
                }
                $itemEach = (float)$selectedPackage->discounted_price;
                if(request('is_annual') === 'true'){
                    $selectedPackage->discounted_price = (float)$selectedPackage->discounted_price * 12;
                    $currentYear = Carbon::now()->year;
                    $selectedPackage->title = $selectedPackage->title.' ( '.$currentYear.' )';
                    $quantity = 12;
                }
            }
            $orderValue = $orderValue + (float)$selectedPackage->discounted_price;
            $createdOrder = new Order([
                'custom_ind' => strtoupper(uniqid()),
                'order_created_by' => $currentUser->id,
                'order_creator_location'=> null,
                'net_value' => $orderValue,
                'base_package_id' => $selectedPackage->id
            ]);
            $createdOrder->save();
            array_push($orderSummery, [
                'item' =>  $selectedPackage->title,
                'quantity' => $quantity,
                'costPerItem' =>  (float)$itemEach,
                'totalCost' => number_format((float)$selectedPackage->discounted_price, 2, '.', '')
            ]);
            if(request()->has('package_addons') && request('package_addons') !== null){
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
                            'order_id' => $createdOrder->custom_ind,
                            'package_addon_id' => $selectedAddon->id,
                            'quantity' => $selectedAddonQuantity
                        ]);
                        $orderAddonCreated->save();
                        array_push($orderSummery, [
                            'item' =>  $selectedAddon->title,
                            'quantity' => (int)$selectedAddonQuantity,
                            'costPerItem' =>  (float)$selectedAddon->discounted_price,
                            'totalCost' => number_format((float)$cost, 2, '.', '')
                        ]);
                    }
                }
            }
            Order::where('id', $createdOrder->id)->update([
               'net_value' => $orderValue
            ]);
            dispatch(new SendOrderConfirmation(
                $createdOrder->custom_ind,
                Carbon::now(),
                $orderSummery,
                $currentUser->email,
                $orderValue
            ));
            return $this->sendResponse([
                'summery' => $orderSummery,
                'total_cost' => number_format((float)$orderValue, 2, '.', ''),
                'order_id' => $createdOrder->custom_ind
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
