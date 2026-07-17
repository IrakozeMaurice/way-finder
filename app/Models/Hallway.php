<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hallway extends Model
{
    protected $fillable=[

        'floor_id',

        'x1',

        'y1',

        'x2',

        'y2'

    ];

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }
}