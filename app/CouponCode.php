<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponCode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'discount', 'is_enabled'
    ];
}
