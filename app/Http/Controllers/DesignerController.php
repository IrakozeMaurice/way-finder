<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\Hallway;
use App\Models\Waypoint;
use App\Models\Location;
use App\Models\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DesignerController extends Controller
{
    public function index($floor)
    {
        $floor = Floor::findOrFail($floor);

        return view('designer.index', compact('floor'));
    }

    public function save(Request $request, $floor)
    {
        $floor = Floor::findOrFail($floor);

        DB::beginTransaction();

        try {

            /*
|--------------------------------------------------------------------------
| Hallways
|--------------------------------------------------------------------------
*/

$currentHallwayIds=[];

foreach($request->hallways as $hallway){

    if(!empty($hallway['db_id'])){

        $h=Hallway::find($hallway['db_id']);

        if($h){

            $h->update([

                'x1'=>$hallway['x1'],
                'y1'=>$hallway['y1'],
                'x2'=>$hallway['x2'],
                'y2'=>$hallway['y2']

            ]);

        }else{

            $h=Hallway::create([

                'floor_id'=>$floor->id,

                'x1'=>$hallway['x1'],
                'y1'=>$hallway['y1'],
                'x2'=>$hallway['x2'],
                'y2'=>$hallway['y2']

            ]);

        }

    }else{

        $h=Hallway::create([

            'floor_id'=>$floor->id,

            'x1'=>$hallway['x1'],
            'y1'=>$hallway['y1'],
            'x2'=>$hallway['x2'],
            'y2'=>$hallway['y2']

        ]);

    }

    $currentHallwayIds[]=$h->id;

}

Hallway::where('floor_id',$floor->id)

->whereNotIn('id',$currentHallwayIds)

->delete();


            /*
            |--------------------------------------------------------------------------
            | Waypoints
            |--------------------------------------------------------------------------
            */

            $waypointMap = [];

            $currentWaypointIds = [];

            foreach ($request->waypoints as $point) {

                if (!empty($point['db_id'])) {

                    $wp = Waypoint::find($point['db_id']);

                    if ($wp) {

                        $wp->update([

                            'x' => $point['x'],

                            'y' => $point['y'],

                            'is_transition' => $point['is_transition'] ?? false,

                            'linked_waypoint_id' => $point['linked_waypoint_id'] ?? null

                        ]);

                    } else {

                        $wp = Waypoint::create([

                            'floor_id' => $floor->id,

                            'x' => $point['x'],

                            'y' => $point['y'],

                            'is_transition' => $point['is_transition'] ?? false,

                            'linked_waypoint_id' => $point['linked_waypoint_id'] ?? null

                        ]);

                    }

                } else {

                    $wp = Waypoint::create([

                        'floor_id' => $floor->id,

                        'x' => $point['x'],

                        'y' => $point['y'],

                        'is_transition' => false,

                        'linked_waypoint_id' => null

                    ]);

                }

                $currentWaypointIds[] = $wp->id;

                $waypointMap[$point['id']] = $wp->id;

            }

            Waypoint::where('floor_id', $floor->id)
                ->whereNotIn('id', $currentWaypointIds)
                ->delete();


            /*
            |--------------------------------------------------------------------------
            | Locations
            |--------------------------------------------------------------------------
            */

            $currentLocationIds=[];

            foreach($request->locations as $location){

                $nearestWaypoint=null;

                $shortestDistance=PHP_FLOAT_MAX;

                foreach($request->waypoints as $waypoint){

                    $dx=$location['x']-$waypoint['x'];

                    $dy=$location['y']-$waypoint['y'];

                    $distance=sqrt($dx*$dx+$dy*$dy);

                    if($distance<$shortestDistance){

                        $shortestDistance=$distance;

                        $nearestWaypoint=$waypoint;

                    }

                }

                $data=[

                    'name'=>$location['name'],

                    'x'=>$location['x'],

                    'y'=>$location['y'],

                    'waypoint_id'=>$nearestWaypoint
                        ? $waypointMap[$nearestWaypoint['id']]
                        : null

                ];

                if(!empty($location['db_id'])){

                    $loc=Location::find($location['db_id']);

                    if($loc){

                        $loc->update($data);

                    }else{

                        $data['floor_id']=$floor->id;

                        $loc=Location::create($data);

                    }

                }else{

                    $data['floor_id']=$floor->id;

                    $loc=Location::create($data);

                }

                $currentLocationIds[]=$loc->id;

            }

            Location::where('floor_id',$floor->id)
            ->whereNotIn('id',$currentLocationIds)
            ->delete();


            /*
            |--------------------------------------------------------------------------
            | Connections
            |--------------------------------------------------------------------------
            */

            $currentConnectionIds=[];

            foreach($request->connections as $connection){

                $data=[

                    'from_waypoint_id'=>$waypointMap[$connection['from']],

                    'to_waypoint_id'=>$waypointMap[$connection['to']],

                    'distance'=>$connection['distance']

                ];

                if(!empty($connection['db_id'])){

                    $c=Connection::find($connection['db_id']);

                    if($c){

                        $c->update($data);

                    }else{

                        $data['floor_id']=$floor->id;

                        $c=Connection::create($data);

                    }

                }else{

                    $data['floor_id']=$floor->id;

                    $c=Connection::create($data);

                }

                $currentConnectionIds[]=$c->id;

            }

            Connection::where('floor_id',$floor->id)
            ->whereNotIn('id',$currentConnectionIds)
            ->delete();


            DB::commit();

            return response()->json([

                'success' => true,

                'message' => 'Designer saved successfully.'

            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([

                'success' => false,

                'message' => $e->getMessage()

            ],500);

        }
    }

    public function load($floor)
    {
        $floor = Floor::findOrFail($floor);

        $hallways = Hallway::where('floor_id',$floor->id)->get();

        $waypoints = Waypoint::with('floor')->where('floor_id',$floor->id)->get();

        $locations = Location::where('floor_id',$floor->id)->get();

        $connections = Connection::where('floor_id',$floor->id)
            ->get();

        return response()->json([

            'hallways'=>$hallways,

            'waypoints'=>$waypoints,

            'locations'=>$locations,

            'connections'=>$connections

        ]);
    }

    public function toggleTransition(Waypoint $waypoint)
    {

        $waypoint->is_transition=

        !$waypoint->is_transition;

        $waypoint->save();

        return response()->json(true);

    }

    public function transitionWaypoints()
    {
        return response()->json(

            \App\Models\Waypoint::with('floor')
                ->where('is_transition',true)
                ->orderBy('floor_id')
                ->get()

        );
    }

    public function linkWaypoint(Request $request, Waypoint $waypoint)
    {

        $waypoint->linked_waypoint_id=

        $request->linked_waypoint_id;

        $waypoint->save();

        return response()->json(true);

    }

}