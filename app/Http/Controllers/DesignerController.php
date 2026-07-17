<?php

namespace App\Http\Controllers;

use App\Models\Floor;

class DesignerController extends Controller
{
    public function index($id)
    {
        $floor = Floor::findOrFail($id);

        return view('designer.index', compact('floor'));
    }
}