<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Services\GraphService;
use App\Services\DijkstraService;
use App\Models\Waypoint;

class NavigationController extends Controller
{
    public function path($startLocation, $endLocation)
    {
        $start = Location::findOrFail($startLocation);

        $end = Location::findOrFail($endLocation);

        $graph = (new GraphService())->build();


        $result =

            (new DijkstraService())

            ->shortestPath(

                $graph,

                $start->waypoint_id,

                $end->waypoint_id

            );


        $waypoints = Waypoint::whereIn('id', $result['path'])->get()->keyBy('id');

        $result['floors'] = [];

        foreach($result['path'] as $id){

            $result['floors'][] = [

                'waypoint' => $id,

                'floor' =>

                $waypoints[$id]->floor_id

            ];

        }

        return response()->json($result);

    }


    public function index()
    {
        $locations=\App\Models\Location::orderBy('name')->get();

        return view('navigation.index', compact('locations'));
    }


    public function floor($floor)
    {
        return response()->json([

            'hallways'=> \App\Models\Hallway::where('floor_id',$floor)->get(),

            'waypoints'=> \App\Models\Waypoint::where('floor_id',$floor)->get(),

            'locations'=> \App\Models\Location::where('floor_id',$floor)->get(),

            'connections'=> \App\Models\Connection::where('floor_id',$floor)->get()

        ]);
    }
}