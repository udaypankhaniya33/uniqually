<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    public function productFeatures(){
        return $this->hasMany('App/ProductFeature');
    }
}
