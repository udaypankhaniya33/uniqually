<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductEntityLocationPrice extends Model
{
    protected $fillable = [
        'product_id',
        'location_id',
        'entity_id',
        'price'
    ];

    public function product(){
        return $this->belongsTo('App/Product');
    }

    public function location(){
        return $this->belongsTo('App/Location');
    }

    public function entity(){
        return $this->belongsTo('App/EntityType');
    }

    public function productFeatures(){
        return $this->hasMany('App/ProductFeature');
    }

    public function productAddonPrices(){
        return $this->hasMany('App/ProductAddonPrice');
    }
}
