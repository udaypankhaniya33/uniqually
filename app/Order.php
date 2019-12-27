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
        'base_package_id'
    ];

}
