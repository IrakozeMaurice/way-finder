<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{

    protected $fillable = [
        'floor_id',
        'from_waypoint_id',
        'to_waypoint_id',
        'distance',
    ];
}