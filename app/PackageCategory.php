<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description_heading', 'description'
    ];

    /**
     * Get the packages for the package category.
     */
    public function comments()
    {
        return $this->hasMany('App\Package');
    }

}
