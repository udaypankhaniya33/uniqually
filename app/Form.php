<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = [
        'name', 
        'is_active'
    ];

    public function formInputs(){
        return $this->hasMany('App\FormInput');
    }

    public function formWizardForms(){
        return $this->hasMany('App\FormWizardForms');
    }
}
