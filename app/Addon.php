<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    protected $fillable = [
        'name',
        'is_active'
    ];

    public function productAddonPrices(){
        return $this->hasMany('App/ProductAddonPrice');
    }
}
