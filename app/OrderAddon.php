<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderAddon extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'package_addon_id',
        'quantity',
        'payment_occurrence',
        'product_addon_price_id'
    ];

    /**
     * Get the order that owns the order addon
     */
    public function order()
    {
        return $this->belongsTo('App\Order');
    }

}
