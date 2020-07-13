<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InputField extends Model
{
    protected $fillable = [
        'type', 
        'limit',
        'is_required'
    ];

    public function formInputs(){
        return $this->hasMany('App\FormInput');
    }
}
