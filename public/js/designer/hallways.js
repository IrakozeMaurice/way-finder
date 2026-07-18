function drawHallways(){

    ctx.strokeStyle="#6b7280";

    ctx.lineWidth=8;

    hallways.forEach(function(h){

        ctx.beginPath();

        ctx.moveTo(h.x1,h.y1);

        ctx.lineTo(h.x2,h.y2);

        ctx.stroke();

    });

}

function startHallway(x,y){

    hallwayStart={

        x:x,

        y:y

    };

}

function finishHallway(x,y){

    let end={

        x:x,

        y:y

    };

    if(

        Math.abs(end.x-hallwayStart.x)

        >

        Math.abs(end.y-hallwayStart.y)

    ){

        end.y=hallwayStart.y;

    }

    else{

        end.x=hallwayStart.x;

    }

    hallways.push({

        x1:hallwayStart.x,

        y1:hallwayStart.y,

        x2:end.x,

        y2:end.y

    });

    hallwayStart=null;

    redraw();

}