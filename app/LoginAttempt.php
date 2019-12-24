<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'authentication_code', 'is_verified', 'created_at', 'updated_at'
    ];
}
