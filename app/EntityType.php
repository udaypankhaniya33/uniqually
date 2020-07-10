<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntityType extends Model
{
    protected $fillable = [
        'name',
        'is_active'
    ];

    public function productEntityLocationPrices(){
        return $this->hasMany('App\ProductEntityLocationPrice', 'entity_id');
    }
}
