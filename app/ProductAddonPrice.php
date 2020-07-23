<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductAddonPrice extends Model
{
    protected $fillable = [
        'product_entity_location_price_id',
        'addon_id',
        'is_included',
        'price',
        'annual_discount'
    ];

    public function productEntityLocationPrice(){
        return $this->belongsTo('App\ProductEntityLocationPrice');
    }

    public function addon(){
        return $this->belongsTo('App\Addon');
    }
}
