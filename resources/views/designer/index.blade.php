@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-6 py-6">

    <div class="flex justify-between items-center mb-6">

        <div>

            <h1 class="text-3xl font-bold">

                {{ $floor->name }}

            </h1>

            <p class="text-gray-500">

                Indoor Navigation Designer

            </p>

        </div>

        <button
            id="saveButton"
            class="bg-green-600 text-white px-6 py-2 rounded">

            Save Designer

        </button>

    </div>


    <div class="grid grid-cols-12 gap-5">

        <!-- LEFT PANEL -->

        <div class="col-span-3">

            <div class="bg-white shadow rounded p-5">

                <h2 class="font-bold text-xl mb-4">

                    Tools

                </h2>

                <button
                    id="hallwayTool"
                    class="toolButton w-full mb-2 bg-blue-600 text-white rounded p-2">

                    Hallway

                </button>

                <button
                    id="waypointTool"
                    class="toolButton w-full mb-2 bg-green-600 text-white rounded p-2">

                    Waypoint

                </button>

                <button
                    id="locationTool"
                    class="toolButton w-full mb-2 bg-purple-600 text-white rounded p-2">

                    Location

                </button>

                <button
                    id="connectTool"
                    class="toolButton w-full bg-orange-600 text-white rounded p-2">

                    Connect

                </button>

            </div>


            <div class="bg-white shadow rounded p-5 mt-5">

                <h2 class="font-bold text-xl mb-3">

                    Guide

                </h2>

                <div
                    id="guide"
                    class="text-sm leading-7">

                    Select a tool to begin.

                </div>

            </div>


            <div class="bg-white shadow rounded p-5 mt-5">

                <div class="flex justify-between">

                    <h2 class="font-bold text-xl">

                        Locations

                    </h2>

                    <button
                        id="newLocation"
                        class="bg-indigo-600 text-white px-3 rounded">

                        +

                    </button>

                </div>


                <div
class="bg-white shadow rounded p-5 mt-5">

<h2 class="font-bold">

Waypoint

</h2>

<div id="waypointInspector">

Click a waypoint.

</div>

</div>


                <div
                    id="locationList"
                    class="mt-4 space-y-2">

                </div>

            </div>

        </div>


        <!-- CANVAS -->

        <div class="col-span-9">

            <div class="bg-white shadow rounded p-3">

                <canvas
                    id="designerCanvas"
                    width="1200"
                    height="700">

                </canvas>

            </div>

        </div>

    </div>

</div>

@endsection


@section('scripts')

    <script>

    const canvas=document.getElementById("designerCanvas");

    const ctx=canvas.getContext("2d");

    let currentTool="";

    let selectedLocation=null;

    let draggingLocation=null;

    let locations=[];

    let hallways=[];

    let waypoints=[];

    let connections=[];

    const GRID=20;



    function snap(value){

        return Math.round(value/GRID)*GRID;

    }



    function drawGrid(){

        ctx.fillStyle="white";

        ctx.fillRect(0,0,canvas.width,canvas.height);

        ctx.strokeStyle="#eeeeee";

        for(let x=0;x<canvas.width;x+=GRID){

            ctx.beginPath();

            ctx.moveTo(x,0);

            ctx.lineTo(x,canvas.height);

            ctx.stroke();

        }

        for(let y=0;y<canvas.height;y+=GRID){

            ctx.beginPath();

            ctx.moveTo(0,y);

            ctx.lineTo(canvas.width,y);

            ctx.stroke();

        }

    }


    function drawHallways(){

        ctx.lineWidth=8;

        ctx.strokeStyle="#888888";

        hallways.forEach(function(h){

            ctx.beginPath();

            ctx.moveTo(h.x1,h.y1);

            ctx.lineTo(h.x2,h.y2);

            ctx.stroke();

        });

    }


    function drawWaypoints(){

        waypoints.forEach(function(point){

            ctx.beginPath();

            ctx.arc(point.x,point.y,7,0,Math.PI*2);

            ctx.fillStyle="#16a34a";

            ctx.fill();

        });

    }


    function drawLocations(){

        locations.forEach(function(location){

            if(location.x===null) return;

            ctx.fillStyle="#2563eb";

            ctx.fillRect(

                location.x,

                location.y,

                130,

                40

            );

            ctx.fillStyle="white";

            ctx.font="15px Arial";

            ctx.fillText(

                location.name,

                location.x+10,

                location.y+25

            );

        });

    }


    function drawConnections(){

        ctx.strokeStyle="red";

        ctx.lineWidth=2;

        ctx.fillStyle="red";

        ctx.font="14px Arial";

        connections.forEach(function(connection){

            let from=waypoints.find(function(point){

                return point.id==connection.from;

            });

            let to=waypoints.find(function(point){

                return point.id==connection.to;

            });

            if(!from || !to){

                return;

            }

            ctx.beginPath();

            ctx.moveTo(from.x,from.y);

            ctx.lineTo(to.x,to.y);

            ctx.stroke();

            let mx=(from.x+to.x)/2;

            let my=(from.y+to.y)/2;

            ctx.fillText(connection.distance,mx,my);

        });

    }


    function showWaypoint(w){

        fetch("/admin/designer/transitions")

        .then(r=>r.json())

        .then(function(list){

            let options="";

            list.forEach(function(item){

                if(item.id==w.id){

                    return;

                }

                options+=`

                <option

                value="${item.id}"

                ${item.id==w.linked_waypoint_id?"selected":""}

                >

                 ${item.floor.name}

                - WP${item.id}

                </option>

                `;

            });

            document.getElementById("waypointInspector").innerHTML=`

                <h3 class="font-bold text-lg">

                Waypoint WP${w.id}

                </h3>

                <p class="mt-2">

                Floor ID:

                ${w.floor_id}

                </p>

                <p>

                Transition:

                ${w.is_transition?"YES":"NO"}

                </p>

                <button

                onclick="toggleTransition(${w.id})"

                class="bg-red-600 text-white px-3 py-2 rounded mt-3">

                Toggle Transition

                </button>

                <hr class="my-4">

                <label class="font-bold">

                Linked Transition

                </label>

                <select

                id="linkedWaypoint"

                class="w-full border rounded p-2 mt-2">

                <option value="">

                None

                </option>

                ${options}

                </select>

                <button

                onclick="saveLinkedWaypoint(${w.id})"

                class="bg-blue-600 text-white px-4 py-2 rounded mt-3">

                Save Link

                </button>

                `;

        });

    }

    function saveLinkedWaypoint(id){

        let linked=

        document
        .getElementById("linkedWaypoint")
        .value;

        fetch(

        "/admin/waypoint/"+id+"/link",

        {

            method:"POST",

            headers:{

                "Content-Type":"application/json",

                "X-CSRF-TOKEN":

                document.querySelector(

                'meta[name="csrf-token"]'

                ).content

            },

            body:JSON.stringify({

                linked_waypoint_id:linked

            })

        })

        .then(r=>r.json())

        .then(function(){

            loadDesigner();

        });

    }


    function toggleTransition(id){

        fetch(

            "/admin/waypoint/"+id+"/toggle",

            {

                method:"POST",

                headers:{

                    "X-CSRF-TOKEN":

                    document.querySelector(

                    'meta[name="csrf-token"]'

                    ).content

                }

            }

        )

        .then(r=>r.json())

        .then(function(){

            loadDesigner();

        });

    }


    function redraw(){

        drawGrid();

        drawHallways();

        drawConnections();

        drawWaypoints();

        drawLocations();

    }

    redraw();



    document.getElementById("hallwayTool").onclick=function(){

        currentTool="hallway";

        document.getElementById("guide").innerHTML=`

            <b>Hallway Tool</b>

            <br><br>

            1. Click starting point.

            <br>

            2. Click ending point.

            <br>

            Only horizontal and vertical hallways will be allowed.

            `;

    };



    document.getElementById("waypointTool").onclick=function(){

        currentTool="waypoint";

        document.getElementById("guide").innerHTML=`

            <b>Waypoint Tool</b>

            <br><br>

            Click on a hallway.

            A green waypoint will be created.

            `;

    };



    document.getElementById("locationTool").onclick=function(){

        currentTool="location";

        document.getElementById("guide").innerHTML=`

            <b>Location Tool</b>

            <br><br>

            1. Click +.

            <br>

            2. Enter location name.

            <br>

            3. Select it.

            <br>

            4. Click canvas.

            <br>

            5. Drag to reposition.

            `;

    };



    document.getElementById("connectTool").onclick=function(){

        currentTool="connect";

        document.getElementById("guide").innerHTML=`

        <b>Connection Tool</b>

        <br><br>

        Click two waypoints.

        Distance will be requested.`;

    };

    document.getElementById("newLocation").onclick=function(){

        let name=prompt("Location Name");

        if(!name) return;

        let location = {

            id:Date.now(),
            db_id:null,

            name:name,

            x:null,

            y:null,

            waypoint_id:null

        };

        locations.push(location);

        refreshLocationList();

    };

    function refreshLocationList(){

        let html="";

        locations.forEach(function(location){

            html+=`

            <button

            onclick="selectLocation(${location.id})"

            class="w-full text-left border rounded p-2 hover:bg-gray-100">

            ${location.name}

            </button>

            `;

        });

        document.getElementById("locationList").innerHTML=html;

    }



    function selectLocation(id){

        selectedLocation=locations.find(function(location){

            return location.id==id;

        });

    }



    let hallwayStart=null;

    let selectedWaypoint=null;



    canvas.onclick=function(e){

        const rect=canvas.getBoundingClientRect();

        let x=snap(e.clientX-rect.left);

        let y=snap(e.clientY-rect.top);


        /* ---------- LOCATION ---------- */

        if(currentTool=="location"){

            if(selectedLocation){

                selectedLocation.x=x;

                selectedLocation.y=y;

                selectedLocation=null;

                redraw();

                return;

            }

        }



        /* ---------- HALLWAY ---------- */

        if(currentTool=="hallway"){

            if(hallwayStart==null){

                hallwayStart={x,y};

                return;

            }

            let end={x,y};



            if(Math.abs(end.x-hallwayStart.x)

            >

            Math.abs(end.y-hallwayStart.y)){

                end.y=hallwayStart.y;

            }

            else{

                end.x=hallwayStart.x;

            }



            hallways.push({

                id:Date.now(),
                db_id:null,

                x1:hallwayStart.x,

                y1:hallwayStart.y,

                x2:end.x,

                y2:end.y

            });



            hallwayStart=null;

            redraw();

            return;

        }



        /* ----------  ---------- */
        let clickedWaypoint=null;

        waypoints.forEach(function(w){

            if(

                Math.abs(w.x-x)<12 &&

                Math.abs(w.y-y)<12

            ){

                clickedWaypoint=w;

            }

        });

        if(

            clickedWaypoint &&

            currentTool!="connect" &&

            currentTool!="waypoint"

        ){

            showWaypoint(clickedWaypoint);

            return;

        }
        /* ----------  ---------- */

        /* ---------- WAYPOINT ---------- */

        if(currentTool=="waypoint"){

            waypoints.push({

                id:Date.now(),
                db_id:null,

                x:x,
                y:y,

                floor_id:FLOOR_ID,

                is_transition:false,

                linked_waypoint_id:null

        });

            redraw();

            return;

        }



        /* ---------- CONNECTION ---------- */

        if(currentTool=="connect"){

            let clicked=null;



            waypoints.forEach(function(point){

                if(

                    Math.abs(point.x-x)<12 &&

                    Math.abs(point.y-y)<12

                ){

                    clicked=point;
                }

            });


            if(clicked==null) return;



            if(selectedWaypoint==null){

                selectedWaypoint=clicked;

                return;

            }



            let distance=prompt("Distance");



            if(distance){

                connections.push({

                    id:Date.now(),
                    db_id:null,

                    from:selectedWaypoint.id,

                    to:clicked.id,

                    distance:distance

                });

            }



            selectedWaypoint=null;

            redraw();

            return;

        }

    };



    canvas.onmousedown=function(e){

        const rect=canvas.getBoundingClientRect();

        let x=e.clientX-rect.left;

        let y=e.clientY-rect.top;



        draggingLocation=null;



        locations.forEach(function(location){

            if(location.x==null)return;



            if(

                x>location.x &&

                x<location.x+130 &&

                y>location.y &&

                y<location.y+40

            ){

                draggingLocation=location;

            }

        });

    };



    canvas.onmousemove=function(e){

        if(draggingLocation==null)return;



        const rect=canvas.getBoundingClientRect();



        draggingLocation.x=snap(e.clientX-rect.left);

        draggingLocation.y=snap(e.clientY-rect.top);



        redraw();

    };



    canvas.onmouseup=function(){

        draggingLocation=null;

    };



    document.getElementById("saveButton").onclick=function(){

        saveDesigner();

    };



    </script>

    <script>

        const FLOOR_ID={{ $floor->id }};

        window.onload=function(){

            loadDesigner();

        };

    </script>

    <script src="{{ asset('js/designer/load.js') }}"></script>

    <script src="{{ asset('js/designer/save.js') }}"></script>


@endsection