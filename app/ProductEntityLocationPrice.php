<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductEntityLocationPrice extends Model
{
    protected $fillable = [
        'product_id',
        'location_id',
        'entity_id',
        'price',
        'annual_discount'
    ];

    public function product(){
        return $this->belongsTo('App\Product');
    }

    public function location(){
        return $this->belongsTo('App\Location');
    }

    public function entity(){
        return $this->belongsTo('App\EntityType', 'entity_id');
    }

    public function productFeatures(){
        return $this->hasMany('App\ProductFeature');
    }

    public function productAddonPrices(){
        return $this->hasMany('App\ProductAddonPrice');
    }

    public function productFormationSteps(){
        return $this->hasMany('App\ProductFormationStep');
    }

    public function formWizardProducts(){
        return $this->hasMany('App\FormWizardProduct');
    }
}
