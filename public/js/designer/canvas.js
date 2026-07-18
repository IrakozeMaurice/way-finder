function snap(value){

    return Math.round(value / GRID) * GRID;

}

function redraw(){

    drawGrid();

    drawHallways();

    drawConnections();

    drawWaypoints();

    drawLocations();

}

function drawGrid(){

    ctx.clearRect(0,0,canvas.width,canvas.height);

    ctx.fillStyle="white";

    ctx.fillRect(0,0,canvas.width,canvas.height);

    ctx.strokeStyle="#e5e7eb";

    ctx.lineWidth=1;

    for(let x=0;x<=canvas.width;x+=GRID){

        ctx.beginPath();

        ctx.moveTo(x,0);

        ctx.lineTo(x,canvas.height);

        ctx.stroke();

    }

    for(let y=0;y<=canvas.height;y+=GRID){

        ctx.beginPath();

        ctx.moveTo(0,y);

        ctx.lineTo(canvas.width,y);

        ctx.stroke();

    }

}