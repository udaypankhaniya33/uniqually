<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Order;
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
        $orders = Order::with('orderAddons')->get();
        foreach ($orders as $index => $order){
            $creator = User::where('id', $order->order_created_by)->first();
            $order->user = [
                'name' => decrypt($creator->name),
                'email' => $creator->email
            ];
        }
        return $this->sendResponse([
            'orders' => $orders,
        ],
            'Successfully retrieved all orders with details');
    }

}
