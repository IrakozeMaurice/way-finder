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

            Hallway::where('floor_id', $floor->id)->delete();

            Waypoint::where('floor_id', $floor->id)->delete();

            Location::where('floor_id', $floor->id)->delete();

            Connection::where('floor_id', $floor->id)->delete();


            /*
            |--------------------------------------------------------------------------
            | Hallways
            |--------------------------------------------------------------------------
            */

            foreach ($request->hallways as $hallway) {

                Hallway::create([

                    'floor_id' => $floor->id,

                    'x1' => $hallway['x1'],

                    'y1' => $hallway['y1'],

                    'x2' => $hallway['x2'],

                    'y2' => $hallway['y2']

                ]);

            }


            /*
            |--------------------------------------------------------------------------
            | Waypoints
            |--------------------------------------------------------------------------
            */

            $waypointMap = [];

            foreach ($request->waypoints as $point) {

                $wp = Waypoint::create([

                    'floor_id' => $floor->id,

                    'x' => $point['x'],

                    'y' => $point['y']

                ]);

                $waypointMap[$point['id']] = $wp->id;

            }


            /*
            |--------------------------------------------------------------------------
            | Locations
            |--------------------------------------------------------------------------
            */

            foreach ($request->locations as $location) {

                Location::create([

                    'floor_id' => $floor->id,

                    'name' => $location['name'],

                    'x' => $location['x'],

                    'y' => $location['y'],

                    'waypoint_id' => null

                ]);

            }


            /*
            |--------------------------------------------------------------------------
            | Connections
            |--------------------------------------------------------------------------
            */

            foreach ($request->connections as $connection) {

                Connection::create([

                    'floor_id' => $floor->id,

                    'from_waypoint_id' =>
                        $waypointMap[$connection['from']],

                    'to_waypoint_id' =>
                        $waypointMap[$connection['to']],

                    'distance' => $connection['distance']

                ]);

            }

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

        $waypoints = Waypoint::where('floor_id',$floor->id)->get();

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

}