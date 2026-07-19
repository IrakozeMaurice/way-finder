let cameraStream = null;

async function openCamera(){

    try{

        cameraStream = await navigator.mediaDevices.getUserMedia({

            video:{
                facingMode:"environment"
            },

            audio:false

        });

        document
        .getElementById("cameraContainer")
        .classList.remove("hidden");

        document
        .getElementById("camera")
        .srcObject = cameraStream;

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

    document
    .getElementById("cameraContainer")
    .classList.add("hidden");

}