<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageAddonAssociation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'package_id','package_addon_id'
    ];
}
