<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waypoint extends Model
{
    protected $fillable=[

        'floor_id',

        'x',

        'y'

    ];

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }
}