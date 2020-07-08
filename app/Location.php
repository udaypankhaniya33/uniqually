<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'location',
        'is_active'
    ];

    public function productEntityLocationPrices(){
        return $this->hasMany('App/ProductEntityLocationPrice');
    }
}
