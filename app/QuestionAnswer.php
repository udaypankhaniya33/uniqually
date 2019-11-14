<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionAnswer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'question', 'answer', 'package_id'
    ];

    /**
     * Get the package that owns the Q&A
     */
    public function package()
    {
        return $this->belongsTo('App\Package');
    }
}
