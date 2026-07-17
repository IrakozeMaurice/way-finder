@extends('layouts.admin')

@section('content')

<div class="flex justify-between items-center mb-6">

    <div>

        <h1 class="text-3xl font-bold">

            {{ $floor->name }}

        </h1>

        <p class="text-gray-500">

            Indoor Navigation Designer

        </p>

    </div>

</div>

<div class="grid grid-cols-4 gap-5">

    <div class="col-span-1">

        <div class="bg-white shadow rounded p-4">

            <h2 class="font-bold text-lg mb-4">

                Tools

            </h2>

            <button id="hallwayTool"
                class="toolButton w-full bg-blue-600 text-white rounded p-2 mb-2">

                Hallway

            </button>

            <button id="waypointTool"
                class="toolButton w-full bg-green-600 text-white rounded p-2 mb-2">

                Waypoint

            </button>

            <button id="locationTool"
                class="toolButton w-full bg-purple-600 text-white rounded p-2 mb-2">

                Location

            </button>

            <button id="connectTool"
                class="toolButton w-full bg-orange-600 text-white rounded p-2">

                Connect

            </button>

        </div>

        <div class="bg-white shadow rounded p-4 mt-5">

            <h2 class="font-bold text-lg mb-3">

                Guide

            </h2>

            <div id="guide">

                Select a tool.

            </div>

        </div>

        <div class="bg-white shadow rounded p-4 mt-5">

            <h2 class="font-bold text-lg mb-3">

                Locations

            </h2>

            <button id="newLocation"
                class="bg-indigo-600 text-white px-4 py-2 rounded w-full">

                + New Location

            </button>

            <div id="locationList" class="mt-4 space-y-2">

            </div>

        </div>

    </div>

    <div class="col-span-3">

        <div class="bg-white shadow rounded">

            <canvas
                id="designer"
                width="1100"
                height="700">
            </canvas>

        </div>

    </div>

</div>

@endsection

@section('scripts')

<script>

const canvas=document.getElementById("designer");

const ctx=canvas.getContext("2d");

let currentTool="";

const guide=document.getElementById("guide");

document.getElementById("hallwayTool").onclick=()=>{

currentTool="hallway";

guide.innerHTML=`
<b>Hallway Tool</b><br><br>

1.Click starting point.<br>

2.Click ending point.<br>

A straight hallway will be created.
`;

};

document.getElementById("waypointTool").onclick=()=>{

currentTool="waypoint";

guide.innerHTML=`
<b>Waypoint Tool</b><br><br>

Click anywhere on a hallway.

A navigation point will appear.
`;

};

document.getElementById("locationTool").onclick=()=>{

currentTool="location";

guide.innerHTML=`
<b>Location Tool</b><br><br>

Select a location from the list.

Click near a hallway to place it.
`;

};

document.getElementById("connectTool").onclick=()=>{

currentTool="connect";

guide.innerHTML=`
<b>Connection Tool</b><br><br>

Click two waypoints.

Distance popup will appear.
`;

};

function drawGrid(){

ctx.clearRect(0,0,canvas.width,canvas.height);

ctx.fillStyle="#ffffff";

ctx.fillRect(0,0,canvas.width,canvas.height);

ctx.strokeStyle="#eeeeee";

for(let x=0;x<canvas.width;x+=20){

ctx.beginPath();

ctx.moveTo(x,0);

ctx.lineTo(x,canvas.height);

ctx.stroke();

}

for(let y=0;y<canvas.height;y+=20){

ctx.beginPath();

ctx.moveTo(0,y);

ctx.lineTo(canvas.width,y);

ctx.stroke();

}

}

drawGrid();

</script>

@endsection