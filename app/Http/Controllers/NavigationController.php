<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Services\GraphService;
use App\Services\DijkstraService;

class NavigationController extends Controller
{
    public function path($startLocation, $endLocation)
    {
        $start = Location::findOrFail($startLocation);

        $end = Location::findOrFail($endLocation);

        $graph = (new GraphService())

            ->build($start->floor_id);


        $result =

            (new DijkstraService())

            ->shortestPath(

                $graph,

                $start->waypoint_id,

                $end->waypoint_id

            );


        return response()->json($result);

    }
}