<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PhpParser\Node\Scalar\String_;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'password', 'type', 'first_name', 'last_name', 'email_verified_at',
        'is_social_auth','activation_code', 'is_am', 'is_tp'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];


    /**
     * Check whether user already exists
     * @param string
     *
     * @return boolean
     */
    public static function isUserExists($email) {
        $count = User::where('email', $email)->count();
        return $count > 0 ? true : false;
    }

    /**
     * Check whether user logged through social media
     * @param string
     *
     * @return boolean
     */
    public static function isSocialMediaUser($email) {
       $user = User::where('email', $email)->first();
       return $user->is_social_auth && $user->password === null ? true : false;
    }
}
