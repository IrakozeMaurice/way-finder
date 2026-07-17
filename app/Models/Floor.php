<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    protected $fillable=[

        'name',

        'floor_plan'

    ];

    public function hallways()
    {
        return $this->hasMany(Hallway::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function waypoints()
    {
        return $this->hasMany(Waypoint::class);
    }
}