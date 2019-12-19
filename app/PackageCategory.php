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
    public function packages()
    {
        return $this->hasMany('App\Package');
    }

    /**
     * Get the A & A for the package category.
     */
    public function questionAnswers()
    {
        return $this->hasMany('App\PackageCategoryQuestionAnswer');
    }

}
