<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormWizardProduct extends Model
{
    
    protected $fillable = [
        'form_wizard_id',
        'product_entity_location_price_id'
    ];

    public function formWizard(){
        return $this->belongsTo('App\FormWizard');
    }

    public function productEntityLocationPrice(){
        return $this->belongsTo('App\ProductEntityLocationPrice');
    }

    public function entityFormationUserData(){
        return $this->hasMany('App\EntityFormationUserData');
    }
}
