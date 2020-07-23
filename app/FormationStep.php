<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormationStep extends Model
{
    protected $fillable = [
        'title',
        'description',
        'img'
    ];

    public function productFormationSteps(){
        return $this->hasMany('App\ProductFormationStep');
    }
}
