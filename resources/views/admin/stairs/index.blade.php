@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-6 py-6">

<h1 class="text-3xl font-bold mb-6">

Stair Connections

</h1>

@if(session('success'))

<div class="bg-green-100 text-green-700 p-3 rounded mb-4">

{{ session('success') }}

</div>

@endif

<div class="bg-white rounded shadow p-6 mb-6">

<h2 class="font-bold text-lg mb-4">

Add Stair Connection

</h2>

<p class="text-gray-500 mb-4">

Connect the bottom waypoint of one floor
to the top waypoint of the next floor.

</p>

<form
method="POST"
action="{{ route('stairs.store') }}">

@csrf

<div class="grid grid-cols-3 gap-4">

<div>

<label>From Waypoint</label>

<select
name="from_waypoint_id"
class="w-full border rounded p-2">

@foreach($waypoints as $waypoint)

<option value="{{ $waypoint->id }}">

Floor {{ $waypoint->floor->name }}

- WP {{ $waypoint->id }}

</option>

@endforeach

</select>

</div>

<div>

<label>To Waypoint</label>

<select
name="to_waypoint_id"
class="w-full border rounded p-2">

@foreach($waypoints as $waypoint)

<option value="{{ $waypoint->id }}">

Floor {{ $waypoint->floor->name }}

- WP {{ $waypoint->id }}

</option>

@endforeach

</select>

</div>

<div>

<label>Distance</label>

<input
type="number"
name="distance"
class="w-full border rounded p-2"
required>

</div>

</div>

<button
class="bg-blue-600 text-white px-6 py-2 rounded mt-6">

Save Connection

</button>

</form>

</div>

<div class="bg-white rounded shadow">

<table class="w-full">

<thead>

<tr class="border-b">

<th class="p-3 text-left">From</th>

<th class="p-3 text-left">To</th>

<th class="p-3">Distance</th>

<th></th>

</tr>

</thead>

<tbody>

@foreach($stairs as $stair)

<tr class="border-b">

<td class="p-3">

{{ $stair->fromWaypoint->floor->name }}

- WP {{ $stair->from_waypoint_id }}

</td>

<td class="p-3">

{{ $stair->toWaypoint->floor->name }}

- WP {{ $stair->to_waypoint_id }}

</td>

<td class="text-center">

{{ $stair->distance }}

steps

</td>

<td>

<form
method="POST"
action="{{ route('stairs.destroy',$stair) }}">

@csrf

@method('DELETE')

<button
class="text-red-600">

Delete

</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

@endsection