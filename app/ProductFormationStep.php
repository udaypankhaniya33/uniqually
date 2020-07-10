<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFormationStep extends Model
{
    protected $fillable = [
        'product_entity_location_price_id',
        'formation_step_id',
        'step'
    ];

    public function productEntityLocationPrice(){
        return $this->belongsTo('App\ProductEntityLocationPrice');
    }

    public function formationStep(){
        return $this->belongsTo('App\FormationStep');
    }
}
