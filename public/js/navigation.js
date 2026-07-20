const canvas = document.getElementById("navigationCanvas");

const ctx = canvas.getContext("2d");

resizeCanvas();

window.addEventListener("resize",resizeCanvas);

function resizeCanvas(){

    canvas.width=canvas.parentElement.clientWidth;

    canvas.height=window.innerHeight*2;

}

let currentFloor = 1;
let currentSegmentIndex = 0;

let instructions=[];
let waitingForQr = false;

let floorSequence = [];

let floorSegments = [];
let currentFloorPath = [];

let hallways = [];
let waypoints = [];
let locations = [];
let connections = [];
let shortestPath = [];

let currentWaypointIndex = 0;

let currentWaypoint = null;

let nextWaypoint = null;

let startLocation = null;

let destinationLocation = null;

let totalDistance = 0;
let arrowPulse=0;

const GRID = 20;

function drawGrid(){

    ctx.clearRect(0,0,canvas.width,canvas.height);

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

    ctx.strokeStyle="#777";
    ctx.lineWidth=8;

    hallways.forEach(function(h){

        ctx.beginPath();
        ctx.moveTo(h.x1,h.y1);
        ctx.lineTo(h.x2,h.y2);
        ctx.stroke();

    });

}

function drawLocations(){

    locations.forEach(function(l){

        ctx.fillStyle="#2563eb";

        ctx.fillRect(l.x,l.y,130,40);

        ctx.fillStyle="white";

        ctx.font="15px Arial";

        ctx.fillText(l.name,l.x+10,l.y+25);

    });

}

function currentTransition(){

    return instructions.find(function(item){

        return item.floor==currentFloor;

    });

}

function drawWaypoints(){

    waypoints.forEach(function(w){

        ctx.beginPath();

        ctx.arc(

            w.x,

            w.y,

            7,

            0,

            Math.PI*2

        );

        if(w.is_transition){

            ctx.fillStyle="#dc2626";

        }else{

            ctx.fillStyle="#16a34a";

        }

        if(w.id==nextWaypoint){

            ctx.fillStyle="#facc15";

        }

        ctx.fill();

        ctx.font="11px Arial";

        ctx.fillStyle="black";

        ctx.fillText(

            "WP"+w.id,

            w.x+10,

            w.y-10

        );

    });

}

function drawShortestPath(){

    if(currentFloorPath.length < 2){

        return;

    }

    for(let i=0;i<currentFloorPath.length-1;i++){

        let from = waypoints.find(function(w){

            return w.id==currentFloorPath[i];

        });

        let to = waypoints.find(function(w){

            return w.id==currentFloorPath[i+1];

        });

        if(!from || !to){

            continue;

        }

        ctx.strokeStyle="#22c55e";
        ctx.lineWidth=8;

        ctx.beginPath();
        ctx.moveTo(from.x,from.y);
        ctx.lineTo(to.x,to.y);
        ctx.stroke();

    }

    if(currentWaypoint && nextWaypoint){

        let from=waypoints.find(w=>w.id==currentWaypoint);

        let to=waypoints.find(w=>w.id==nextWaypoint);

        if(from && to){

            drawArrow(from,to);

        }

    }

}

function drawArrow(from,to){

    const angle=Math.atan2(

        to.y-from.y,

        to.x-from.x

    );

    const percent=0.70;

    const midX=from.x+(to.x-from.x)*percent;

    const midY=from.y+(to.y-from.y)*percent;

    const size=18+Math.sin(arrowPulse)*3;

    ctx.save();

    ctx.translate(midX,midY);

    ctx.rotate(angle);

    ctx.fillStyle="red";

    ctx.beginPath();

    ctx.moveTo(size,0);

    ctx.lineTo(-size,-6);

    ctx.lineTo(-size,6);

    ctx.closePath();

    ctx.fill();

    ctx.restore();

}

function drawStartAndDestination(){

    if(startLocation && startLocation.floor_id == currentFloor){

        ctx.beginPath();

        ctx.arc(

            startLocation.x+65,

            startLocation.y+20,

            18,

            0,

            Math.PI*2

        );

        ctx.fillStyle="green";

        ctx.fill();

        ctx.fillStyle="white";

        ctx.font="bold 18px Arial";

        ctx.fillText(

            "S",

            startLocation.x+61,

            startLocation.y+24

        );

    }


    if(destinationLocation && destinationLocation.floor_id == currentFloor){

        ctx.beginPath();

        ctx.arc(

            destinationLocation.x+65,

            destinationLocation.y+20,

            18,

            0,

            Math.PI*2

        );

        ctx.fillStyle=(nextWaypoint==null) ? "#16a34a" : "#dc2626";

        ctx.fill();

        ctx.fillStyle="white";

        ctx.font="bold 18px Arial";

        ctx.fillText(

            "D",

            destinationLocation.x+61,

            destinationLocation.y+24

        );

    }

}

function redraw(){

    drawGrid();

    drawHallways();

    drawShortestPath();

    drawWaypoints();

    drawLocations();

    drawStartAndDestination();

}

function getCurrentFloorSegment(){

    let segment = floorSegments.find(function(segment){

        return segment.floor == currentFloor;

    });

    if(segment){

        return segment.path;

    }

    return [];

}

function loadCurrentSegment(){

    currentFloor = floorSegments[currentSegmentIndex].floor;

    currentFloorPath = floorSegments[currentSegmentIndex].path;

    currentWaypointIndex = 0;

    currentWaypoint = currentFloorPath[0];

    nextWaypoint = currentFloorPath[1] ?? null;

    loadFloor(currentFloor);

}

function nextFloor(){

    waitingForQr = false;

    if(currentSegmentIndex >= floorSegments.length-1){

        routeCompleted();

        return;

    }

    currentSegmentIndex++;

    loadCurrentSegment();

}

function reachedTransition(){

    waitingForQr = true;

    document
        .getElementById("transitionOverlay")
        .classList.remove("hidden");

    let nextFloorName="";

    if(currentSegmentIndex+1 < floorSegments.length){

        nextFloorName = floorSegments[currentSegmentIndex+1].floor;

    }

    document
        .getElementById("transitionMessage")
        .innerHTML=

        `
        <div class="text-xl font-bold mb-3">

        Floor Transition

        </div>

        <div>

        Go upstairs.

        </div>

        <div class="mt-2">

        Scan the QR code on Floor ${nextFloorName}.

        </div>
        `;

}

function showTransitionScreen(transition){

    document.getElementById("transitionOverlay")

    .classList.remove("hidden");

    document.getElementById("transitionMessage")

    .innerHTML=

    transition.message;

}

function routeCompleted(){

    document.getElementById("routeProgress").innerHTML=

    "✓ Destination Reached";

    document.getElementById("distanceLabel").innerHTML="0 steps";

}

function moveToNextWaypoint(){

    if(waitingForQr){

        return;

    }

    if(currentWaypointIndex >= currentFloorPath.length-1){

        if(currentSegmentIndex < floorSegments.length-1){

            reachedTransition();

        }else{

            routeCompleted();

        }

        return;

    }

    currentWaypointIndex++;

    updateNavigationProgress();

}

setInterval(function(){

    arrowPulse+=0.25;

    redraw();

},100);

setInterval(function(){

    if(waitingForQr){

        return;

    }

    if(shortestPath.length==0){

        return;

    }

    moveToNextWaypoint();

},5000);

function updateNavigationProgress(){

    currentWaypoint = currentFloorPath[currentWaypointIndex];

    nextWaypoint = currentFloorPath[currentWaypointIndex+1] ?? null;

    redraw();

}