<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Charity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'charity_code', 'charity_org_name', 'charity_org_location', 'give', 'is_active'
    ];
}
