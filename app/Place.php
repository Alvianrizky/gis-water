<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'place_name',
        'address',
        'description',
        'longitude',
        'latitude',
    ];
}
