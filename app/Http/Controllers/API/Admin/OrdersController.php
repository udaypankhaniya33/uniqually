<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Order;
use App\OrderStatus;
use App\Package;
use App\PackageAddon;
use App\User;
use Illuminate\Http\Request;

class OrdersController extends BaseController
{

    /**
     * Get all orders with all relevant data
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $orders = Order::with('orderAddons')->orderBy('created_at', 'desc')->get();
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
        $orderStatuses = OrderStatus::all();
        return $this->sendResponse([
            'orders' => $orders,
            'orderStatuses' => $orderStatuses
        ],
            'Successfully retrieved all orders with details');
    }

    /**
     * Update order status of an order
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function update(){
        if(request()->has('order_id', 'status_id')){
            $order = Order::find(request('order_id'));
            $order->order_status = (int)request('status_id');
            $order->save();
            return $this->sendResponse([
                'order' => $order
            ],
                'Successfully updated order status');
        }else{
            return $this->sendError('Please provide valid data', [
                'error' => 'Order Id and Status Id required!'
            ], 422);
        }
    }

}
