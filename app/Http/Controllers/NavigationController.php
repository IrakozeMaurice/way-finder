<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Services\GraphService;
use App\Services\DijkstraService;
use App\Models\Waypoint;

class NavigationController extends Controller
{
    
    public function path($startLocation,$endLocation)
    {
        $start=Location::findOrFail($startLocation);

        $end=Location::findOrFail($endLocation);

        $graph=(new GraphService())->build();

        $result=(new DijkstraService())->shortestPath(

            $graph,

            $start->waypoint_id,

            $end->waypoint_id

        );

        $waypoints=Waypoint::whereIn(
            'id',
            $result['path']
        )->get()->keyBy('id');

        /*
        -----------------------------------
        Floor segments
        -----------------------------------
        */

        $segments=[];

        $currentFloor=null;

        foreach($result['path'] as $waypointId){

            $floor=$waypoints[$waypointId]->floor_id;

            if(!isset($segments[$floor])){

                $segments[$floor]=[
                    'floor'=>$floor,
                    'path'=>[]
                ];

            }

            $segments[$floor]['path'][]=$waypointId;

        }

        /*
        -----------------------------------
        Instructions
        -----------------------------------
        */

        $instructions=[];

        foreach($result['path'] as $waypointId){

            $wp=$waypoints[$waypointId];

            if($wp->is_transition){

                $instructions[]=[

                    'type'=>'transition',

                    'waypoint'=>$wp->id,

                    'floor'=>$wp->floor_id,

                    'message'=>'Go upstairs and scan the QR code.'

                ];

            }

        }

        return response()->json([

            'path'=>$result['path'],

            'distance'=>$result['distance'],

            'segments'=>array_values($segments),

            'instructions'=>$instructions

        ]);
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