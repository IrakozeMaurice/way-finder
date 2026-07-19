@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-6 py-6">

<h1 class="text-3xl font-bold mb-6">

Indoor Navigation

</h1>


<div class="grid grid-cols-12 gap-6">

<div class="col-span-3">

<div class="bg-white shadow rounded p-5">

<label class="font-bold">

Start

</label>

<select
id="start"
class="w-full border rounded p-2 mt-2">

@foreach($locations as $location)

<option value="{{ $location->id }}">

{{ $location->name }}

</option>

@endforeach

</select>


<label
class="font-bold block mt-5">

Destination

</label>

<select
id="destination"
class="w-full border rounded p-2 mt-2">

@foreach($locations as $location)

<option value="{{ $location->id }}">

{{ $location->name }}

</option>

@endforeach

</select>


<button
id="navigateButton"
class="w-full bg-blue-600 text-white p-2 rounded mt-5">

Navigate

</button>

</div>

</div>


<div class="col-span-9">

<div class="bg-white rounded shadow p-3">

<canvas

id="navigationCanvas"

width="1200"

height="700">

</canvas>

</div>

</div>

</div>

</div>

<div
id="navigationStatus"
class="mt-4 bg-blue-50 border border-blue-200 rounded p-4">

<b>Navigation Ready</b>

<p class="text-gray-600 mt-2">

Select a start location and destination,
then click <b>Navigate</b>.

</p>

</div>

<script src="{{ asset('js/navigation.js') }}"></script>

<script>

    const FLOOR_ID=1;

    loadFloor();

    function loadFloor(){

    fetch("/navigation/floor/"+FLOOR_ID)

    .then(response=>response.json())

    .then(data=>{

    hallways=data.hallways;

    waypoints=data.waypoints;

    locations=data.locations;

    connections=data.connections;

    redraw();

    });

    }

</script>

<script>

document.getElementById("navigateButton").onclick = function () {

    let start = parseInt(document.getElementById("start").value);

    let destination = parseInt(document.getElementById("destination").value);

    startLocation = null;

    destinationLocation = null;

    // Start location is always on the currently displayed floor
    startLocation = locations.find(function(location){

        return Number(location.id) === Number(start);

    });

    // Destination is only visible if it is on the current floor
    destinationLocation = locations.find(function(location){

        return Number(location.id) === Number(destination);

    });


    fetch("/navigation/" + start + "/" + destination)

    .then(response => response.json())

    .then(data => {

        shortestPath = data.path;

        floorSequence = data.floors;

        floorSegments = data.segments;

        currentFloor = floorSegments[0].floor;

        totalDistance = data.distance;

        redraw();

        document.getElementById("navigationStatus").innerHTML = `

        <div class="bg-blue-50 border rounded p-4">

            <div class="text-green-700 font-bold">

                🟢 Start:

                ${data.start_name}

            </div>

            <div class="text-red-700 font-bold mt-2">

                🔴 Destination:

                ${data.destination_name}

            </div>

            <div class="mt-3">

                📏 Total Distance:

                <b>${totalDistance} steps</b>

            </div>

            <div class="mt-3 text-blue-700">

                ✓ Route Ready

            </div>

        </div>

        `;

    });

};

</script>

@endsection