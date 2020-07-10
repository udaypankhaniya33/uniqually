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
}
