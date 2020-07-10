<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFeature extends Model
{
    protected $fillable = [
        'product_entity_location_price_id',
        'feature_id'
    ];

    public function productEntityLocationPrice(){
        return $this->belongsTo('App\ProductEntityLocationPrice');
    }

    public function feature(){
        return $this->belongsTo('App\Feature');
    }
}
