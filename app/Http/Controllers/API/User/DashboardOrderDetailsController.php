<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use App\Order;
use App\Package;
use App\PackageAddon;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardOrderDetailsController extends BaseController
{


    /**
     * Submit order
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $orders = Order::with('orderAddons')->where('order_created_by', Auth::id())->orderBy('created_at', 'desc')->get();
        foreach ($orders as $index => $order){
            $creator = User::where('id', $order->order_created_by)->first();
            $package = Package::where('id', $order->base_package_id)->with('packageCategory')->first();
            $order->basePaackage = [
                'name' => $package->packageCategory->title.' - '.$package->title,
                'price' => $package->discounted_price
            ];
            $order->user = [
                'name' => decrypt($creator->name),
                'email' => $creator->email
            ];
            foreach ($order->orderAddons as $addon){
                $addonData = PackageAddon::where('id', $addon->package_addon_id)->first();
                $addon->name = $addonData->title;
                $addon->price = $addonData->discounted_price;
            }
        }
        return $this->sendResponse([
            'orders' => $orders,
        ],
            'Successfully retrieved all orders with details');
    }
}
