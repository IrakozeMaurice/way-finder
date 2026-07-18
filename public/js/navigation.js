const canvas = document.getElementById("navigationCanvas");

const ctx = canvas.getContext("2d");

let hallways = [];
let waypoints = [];
let locations = [];
let connections = [];
let shortestPath = [];

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

        ctx.arc(w.x,w.y,7,0,Math.PI*2);

        ctx.fillStyle="#16a34a";

        ctx.fill();

    });

}

function drawShortestPath(){

    if(shortestPath.length<2){

        return;

    }

    ctx.strokeStyle="red";

    ctx.lineWidth=5;

    for(let i=0;i<shortestPath.length-1;i++){

        let from=waypoints.find(w=>w.id==shortestPath[i]);

        let to=waypoints.find(w=>w.id==shortestPath[i+1]);

        if(!from || !to){

            continue;

        }

        ctx.beginPath();

        ctx.moveTo(from.x,from.y);

        ctx.lineTo(to.x,to.y);

        ctx.stroke();

    }

}

function redraw(){

    drawGrid();

    drawHallways();

    drawShortestPath();

    drawWaypoints();

    drawLocations();

}