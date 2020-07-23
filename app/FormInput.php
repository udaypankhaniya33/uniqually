<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormInput extends Model
{
    protected $fillable = [
        'form_id',
        'input_id',
        'display_order'
    ];

    public function form(){
        return $this->belongsTo('App\Form');
    }

    public function inputField(){
        return $this->belongsTo('App\InputField', 'input_id');
    }
}
