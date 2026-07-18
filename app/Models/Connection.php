<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    protected $fillable = [

        'floor_id',

        'from_waypoint_id',

        'to_waypoint_id',

        'distance'

    ];

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

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