<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntityFormationUserData extends Model
{
    protected $fillable = [
        'user_id',
        'form_wizard_product_id'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function formWizardProduct(){
        return $this->belongsTo('App\FormWizardProduct');
    }
}
