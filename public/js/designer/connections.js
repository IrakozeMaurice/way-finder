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