let cameraStream = null;

async function openCamera(){

    try{

        cameraStream = await navigator.mediaDevices.getUserMedia({

            video:{
                facingMode:"environment"
            },

            audio:false

        });

        document.getElementById("cameraContainer").classList.remove("hidden");

        document.getElementById("camera").srcObject = cameraStream;

        updateAROverlay();

        console.log("AR Navigation Ready");

    }

    catch(error){

        alert("Unable to access camera.");

        console.log(error);

    }

}

function closeCamera(){

    if(cameraStream){

        cameraStream.getTracks().forEach(function(track){

            track.stop();

        });

    }

    document.getElementById("cameraContainer").classList.add("hidden");

}


function updateAROverlay(){

    document.getElementById("arFloor").innerHTML=

    document.getElementById("currentFloor").innerHTML;

    document.getElementById("arDistance").innerHTML=

    document.getElementById("distanceLabel").innerHTML;

}


setInterval(function(){

    let arrow=document.getElementById("arArrow");

    if(!arrow) return;

    arrow.style.transform=

    "translateY(-8px)";

    setTimeout(function(){

        arrow.style.transform=

        "translateY(8px)";

    },400);

},800);