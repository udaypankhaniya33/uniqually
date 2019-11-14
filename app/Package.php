<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'price', 'package_category_id'
    ];

    /**
     * Get the package attributes for the package.
     */
    public function attributes()
    {
        return $this->hasMany('App\PackageAttribute');
    }

    /**
     * Get the package category that owns the package
     */
    public function packageCategory()
    {
        return $this->belongsTo('App\PackageCategory');
    }

}
