<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_created_by',
        'order_creator_location',
        'net_value',
        'base_package_id',
        'custom_ind',
        'order_status',
        'is_entity_order',
        'product_entity_location_price_id',
        'payment_occurrence'
    ];

    /**
     * Get the order addons
     */
    public function orderAddons()
    {
        return $this->hasMany('App\OrderAddon');
    }

}
