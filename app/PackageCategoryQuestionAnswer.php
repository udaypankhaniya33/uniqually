<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageCategoryQuestionAnswer extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question', 'answer', 'package_category_id'
    ];

    /**
     * Get the package category that owns the Q&A
     */
    public function packageCategory()
    {
        return $this->belongsTo('App\PackageCategory');
    }


}
