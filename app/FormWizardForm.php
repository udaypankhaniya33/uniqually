<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormWizardForm extends Model
{
    protected $fillable = [
        'form_wizard_id',
        'form_id',
        'position'
    ];

    public function form(){
        return $this->belongsTo('App\Form');
    }

    public function formWizard(){
        return $this->belongsTo('App\FormWizard');
    }
}
