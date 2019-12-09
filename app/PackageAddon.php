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
        'title', 'price', 'discounted_price', 'package_id'
    ];

    /**
     * Get the package that owns the attribute
     */
    public function package()
    {
        return $this->belongsTo('App\Package');
    }
}
