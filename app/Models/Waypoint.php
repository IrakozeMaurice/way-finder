<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waypoint extends Model
{
    protected $fillable = [
        'floor_id',
        'x',
        'y'
    ];

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function connectionsFrom()
    {
        return $this->hasMany(
            Connection::class,
            'from_waypoint_id'
        );
    }

    public function connectionsTo()
    {
        return $this->hasMany(
            Connection::class,
            'to_waypoint_id'
        );
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }
}