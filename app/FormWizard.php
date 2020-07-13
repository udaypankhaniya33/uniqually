<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormWizard extends Model
{
    protected $fillable = [
        'name'
    ];

    public function formWizardForms(){
        return $this->hasMany('App\FormWizardForms');
    }
}
