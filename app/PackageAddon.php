<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageAddon extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'price', 'discounted_price', 'description', 'maximum_quantity', 'minimum_quantity'
    ];
}
