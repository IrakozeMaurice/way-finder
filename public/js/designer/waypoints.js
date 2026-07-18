function drawWaypoints(){

    waypoints.forEach(function(point){

        ctx.beginPath();

        ctx.arc(

            point.x,

            point.y,

            8,

            0,

            Math.PI*2

        );

        ctx.fillStyle="#16a34a";

        ctx.fill();

    });

}

function addWaypoint(x,y){

    waypoints.push({

        id:Date.now(),

        x:x,

        y:y

    });

    redraw();

}