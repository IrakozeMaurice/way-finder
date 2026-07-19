<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StairConnection extends Model
{
    protected $fillable=[

        'from_waypoint_id',

        'to_waypoint_id',

        'distance'

    ];

    public function fromWaypoint()
    {
        return $this->belongsTo(
            Waypoint::class,
            'from_waypoint_id'
        );
    }

    public function toWaypoint()
    {
        return $this->belongsTo(
            Waypoint::class,
            'to_waypoint_id'
        );
    }
}