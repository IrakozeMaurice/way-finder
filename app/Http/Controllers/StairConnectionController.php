<?php

namespace App\Http\Controllers;

use App\Models\StairConnection;
use App\Models\Waypoint;
use Illuminate\Http\Request;

class StairConnectionController extends Controller
{
    public function index()
    {
        $stairs = StairConnection::with([
            'fromWaypoint.floor',
            'toWaypoint.floor'
        ])->get();

        $waypoints = Waypoint::with('floor')
            ->orderBy('floor_id')
            ->orderBy('id')
            ->get();

        return view(
            'admin.stairs.index',
            compact('stairs','waypoints')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_waypoint_id'=>'required',
            'to_waypoint_id'=>'required',
            'distance'=>'required|integer|min:1'
        ]);

        StairConnection::create($request->all());

        return back()->with(
            'success',
            'Stair connection saved.'
        );
    }

    public function destroy(StairConnection $stair)
    {
        $stair->delete();

        return back()->with(
            'success',
            'Deleted.'
        );
    }
}