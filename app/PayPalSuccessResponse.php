<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayPalSuccessResponse extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'response', 'order_id'
    ];


}
