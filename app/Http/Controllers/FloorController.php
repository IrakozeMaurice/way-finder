<?php

namespace App\Http\Controllers;

use App\Models\Floor;

class FloorController extends Controller
{
    public function index()
    {
        $floors=Floor::all();

        return view('floors.index',compact('floors'));
    }

    public function qr(Floor $floor)
    {
        return view('floors.qr', compact('floor'));
    }
}