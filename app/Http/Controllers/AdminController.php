<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Floor;
use App\Models\Location;
use App\Models\Waypoint;
use App\Models\Connection;
use App\Models\Hallway;


class AdminController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {

            return back()->with('error', 'Invalid email or password.');
        }

        session([
            'admin_id' => $admin->id,
            'admin_name' => $admin->name
        ]);

        return redirect('/admin/dashboard');
    }

    public function dashboard()
    {
        $floors = Floor::all()->count();
        $locations = Location::all()->count();
        $waypoints = Waypoint::all()->count();
        $connections = Connection::all()->count();
        $hallways = Hallway::all()->count();

        return view('admin.dashboard', compact('floors','locations','waypoints','connections', 'hallways'));
    }

    public function logout()
    {
        session()->flush();

        return redirect('/admin/login');
    }
}