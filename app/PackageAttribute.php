<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageAttribute extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'package_id', 'attribute'
    ];

    /**
     * Get the package that owns the attribute
     */
    public function package()
    {
        return $this->belongsTo('App\Package');
    }

}
