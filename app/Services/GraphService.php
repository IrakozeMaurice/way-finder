<?php

namespace App\Services;

use App\Models\Connection;

class GraphService
{
    public function build($floorId)
    {
        $graph=[];

        $connections=Connection::where(

            'floor_id',

            $floorId

        )->get();

        foreach($connections as $connection){

            $from=$connection->from_waypoint_id;

            $to=$connection->to_waypoint_id;

            $distance=$connection->distance;

            $graph[$from][]=[

                'node'=>$to,

                'distance'=>$distance

            ];

            $graph[$to][]=[

                'node'=>$from,

                'distance'=>$distance

            ];

        }

        return $graph;

    }
}