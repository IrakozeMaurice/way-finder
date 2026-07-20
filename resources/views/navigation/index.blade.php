@extends('layouts.admin')

@section('content')

<div class="min-h-screen bg-gray-100">

<div class="max-w-md mx-auto bg-white shadow-lg">
<!-- <div class="max-w-mdd mx-auto bg-red-200d shadow-lg"> -->

    <!-- Header -->

    <div class="bg-blue-600 text-white p-4">

        <h1 class="text-xl font-bold">

            Indoor Navigation

        </h1>

        <p class="text-sm opacity-80">

            University of Kigali

        </p>

    </div>


    <!-- Controls -->

    <div class="p-4 space-y-4">

        <div>

            <label class="font-semibold">

                Start

            </label>

            <select

                id="start"

                class="w-full border rounded-lg p-3 mt-2">

                @foreach($locations as $location)

                    <option value="{{ $location->id }}">

                        {{ $location->name }}

                    </option>

                @endforeach

            </select>

        </div>


        <div>

            <label class="font-semibold">

                Destination

            </label>

            <select

                id="destination"

                class="w-full border rounded-lg p-3 mt-2">

                @foreach($locations as $location)

                    <option value="{{ $location->id }}">

                        {{ $location->name }}

                    </option>

                @endforeach

            </select>

        </div>


        <button

            id="navigateButton"

            class="w-full bg-blue-600 hover:bg-blue-700 text-white text-lg font-bold rounded-lg py-4">

            START NAVIGATION

        </button>

    </div>


    <!-- Status Card -->

    <div id="navigationStatus"

        class="mx-4 mb-4 rounded-xl bg-blue-50 border border-blue-300 shadow p-4">

        <div id="transitionOverlay"
            class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50">

            <div class="bg-white rounded-lg shadow-xl p-8 w-96 text-center">

            <h2 class="text-2xl font-bold mb-4">

            Floor Transition

            </h2>

            <p id="transitionMessage" class="mb-6"></p>

            <div id="qr-reader" class="w-full mt-4"></div>


            </div>

            </div>

        <div class="text-lg font-bold">

            Ready

        </div>

        <div class="text-gray-500 mt-2">

            Select start and destination.

        </div>

    </div>


    <!-- Floor Card -->

    <div

        class="mx-4 mb-4 rounded-lg border bg-white shadow p-4">

        <div class="text-sm text-gray-500">

            Current Floor

        </div>

        <div

            id="currentFloor"

            class="text-2xl font-bold mt-2">

            Ground Floor

        </div>

    </div>


    <!-- Distance Card -->

    <div

        class="mx-4 mb-4 rounded-lg border bg-white shadow p-4">

        <div class="text-sm text-gray-500">

            Remaining Distance

        </div>

        <div

            id="distanceLabel"

            class="text-2xl font-bold mt-2">

            --

        </div>

    </div>

    <div class="mx-4 mb-4 rounded-lg border bg-white shadow p-4">

        <div class="text-sm text-gray-500">

        Navigation

        </div>

        <div id="routeProgress"

        class="text-lg font-semibold mt-2">

        Waiting...

        </div>

    </div>


    <!-- Canvas -->

    <div class="mx-2 mb-4">

        <canvas id="navigationCanvas" class="w-full rounded-lg border shadow">

        </canvas>

    </div>

    <div class="fixed bottom-6 right-6">

        <button id="cameraButton"

            class="w-16 h-16 rounded-full bg-green-600 text-white shadow-xl text-3xl">

                📷

        </button>

    </div>

</div>

</div>


<div id="cameraContainer" class="hidden fixed inset-0 bg-black z-50">

    <video id="camera" autoplay playsinline
        class="absolute inset-0 w-full h-full object-cover">
        <div

id="arOverlay"

class="absolute inset-0 pointer-events-none">

    <div

    class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">

        <div

        id="arArrow" class="text-red-500 drop-shadow-2xl" style="font-size:180px;">

            ↑

        </div>

    </div>

</div>
    </video>

    <div

class="absolute bottom-0 left-0 right-0 bg-black/70 text-white p-5">

    <div
    id="arFloor"
    class="text-xl font-bold">

        Ground Floor

    </div>

    <div
    id="arDistance"
    class="mt-2">

        --

    </div>

</div>

    <button
        id="closeCamera"
        class="absolute top-4 right-4 bg-red-600 text-white px-4 py-2 rounded-lg">

        Close

    </button>

</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script src="{{ asset('js/navigation.js') }}"></script>
<script src="{{ asset('js/camera.js') }}"></script>

<script>
    const FLOOR_NAMES={

        1:"Ground Floor",

        2:"First Floor",

        3:"Second Floor",

        4:"Third Floor"

    };

    loadFloor(1);

    function loadFloor(floor){

        fetch("/navigation/floor/"+floor)

        .then(response=>response.json())

        .then(data=>{

            hallways=data.hallways;

            waypoints=data.waypoints;

            locations=data.locations;

            connections=data.connections;

            redraw();

            document.getElementById("currentFloor").innerHTML = FLOOR_NAMES[floor];

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
            currentWaypointIndex=0;

            updateNavigationProgress();

            floorSequence = data.floors;

            floorSegments = data.segments;
            instructions=data.instructions;

            currentSegmentIndex = 0;

            currentSegmentIndex = 0;

            loadCurrentSegment();

            return;


            currentFloor = floorSegments[0].floor;

            totalDistance = data.distance;

            document.getElementById("routeProgress").innerHTML="Following shortest path...";

            document.getElementById("distanceLabel").innerHTML=data.distance+" steps";

            if(data.floors.length){

                let floor=data.floors[0].floor;

                document.getElementById("currentFloor").innerHTML=

                FLOOR_NAMES[floor];

            }

            // redraw();

            document.getElementById("navigationStatus").innerHTML = `
                <div class="space-y-2">

                <div>

                <b>Start</b>

                <br>

                ${startLocation.name}

                </div>

                <div>

                <b>Destination</b>

                <br>

                ${destinationLocation.name}

                </div>

                <div>

                <b>Total Distance</b>

                <br>

                ${totalDistance} steps

                </div>

                <div class="text-green-700 font-bold">

                Navigation Active

                </div>

                </div>`
            ;

        });

    };

</script>

<script>
    document.getElementById("cameraButton").onclick=function(){
        openCamera();
    };

    document.getElementById("closeCamera").onclick=function(){
        closeCamera();
    };

</script>

@endsection