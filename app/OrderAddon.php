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
        'quantity'
    ];

}
