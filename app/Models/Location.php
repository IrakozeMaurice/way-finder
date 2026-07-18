<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [

        'floor_id',

        'name',

        'x',

        'y',

        'waypoint_id'

    ];

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function waypoint()
    {
        return $this->belongsTo(Waypoint::class);
    }
}