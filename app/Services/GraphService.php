<?php

namespace App\Services;

use App\Models\Connection;
use App\Models\StairConnection;

class GraphService
{
    public function build()
    {
        $graph=[];

        foreach(Connection::all() as $connection){

            $graph[$connection->from_waypoint_id][]= [

                'node'=>$connection->to_waypoint_id,

                'distance'=>$connection->distance

            ];

            $graph[$connection->to_waypoint_id][]= [

                'node'=>$connection->from_waypoint_id,

                'distance'=>$connection->distance

            ];

        }

        foreach(StairConnection::all() as $connection){

            $graph[$connection->from_waypoint_id][]= [

                'node'=>$connection->to_waypoint_id,

                'distance'=>$connection->distance

            ];

            $graph[$connection->to_waypoint_id][]= [

                'node'=>$connection->from_waypoint_id,

                'distance'=>$connection->distance

            ];

        }

        return $graph;
    }
}