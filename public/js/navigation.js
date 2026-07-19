const canvas = document.getElementById("navigationCanvas");

const ctx = canvas.getContext("2d");

let floorSequence=[];

let hallways = [];
let waypoints = [];
let locations = [];
let connections = [];
let shortestPath = [];

let startLocation = null;

let destinationLocation = null;

let totalDistance = 0;

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

    if(shortestPath.length<2){

        return;

    }

    ctx.strokeStyle="#adef44";

    ctx.lineWidth=5;

    for(let i=0;i<shortestPath.length-1;i++){

        let from=

        waypoints.find(function(w){

            return w.id==shortestPath[i];

        });

        let to=

        waypoints.find(function(w){

            return w.id==shortestPath[i+1];

        });

        if(!from || !to){

            continue;

        }

        ctx.beginPath();

        ctx.moveTo(

            from.x,

            from.y

        );

        ctx.lineTo(

            to.x,

            to.y

        );

        ctx.stroke();

        drawArrow(from,to);

    }

}

function drawArrow(from,to){

    const angle=Math.atan2(

        to.y-from.y,

        to.x-from.x

    );

    const midX=(from.x+to.x)/2;

    const midY=(from.y+to.y)/2;

    const size=12;

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

    if(startLocation){

        ctx.beginPath();

        ctx.arc(

            startLocation.x+65,

            startLocation.y+20,

            12,

            0,

            Math.PI*2

        );

        ctx.fillStyle="green";

        ctx.fill();

        ctx.fillStyle="white";

        ctx.font="bold 12px Arial";

        ctx.fillText(

            "S",

            startLocation.x+61,

            startLocation.y+24

        );

    }


    if(destinationLocation){

        ctx.beginPath();

        ctx.arc(

            destinationLocation.x+65,

            destinationLocation.y+20,

            12,

            0,

            Math.PI*2

        );

        ctx.fillStyle="red";

        ctx.fill();

        ctx.fillStyle="white";

        ctx.font="bold 12px Arial";

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

    shortestPath = getCurrentFloorPath(FLOOR_ID);

    drawShortestPath();

    drawWaypoints();

    drawLocations();

    drawStartAndDestination();

}

function getCurrentFloorPath(floor){

    return shortestPath.filter(function(id){

        let item=floorSequence.find(function(f){

            return f.waypoint==id;

        });

        return item && item.floor==floor;

    });

}