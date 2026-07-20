let qrReader = null;
let qrScanner = null;
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

function startQrScanner(){

    if(qrScanner){

        qrScanner.clear();

    }

    qrScanner = new Html5Qrcode("qr-reader");

    qrScanner.start(

        {
            facingMode:"environment"
        },

        {
            fps:10,
            qrbox:250
        },

        function(decodedText){

            let floor=parseInt(decodedText);

            if(isNaN(floor)){

                return;

            }

            if(floor!=floorSegments[currentSegmentIndex+1].floor){

                alert("Wrong floor QR.");

                return;

            }

            qrScanner.stop();

            document

                .getElementById("transitionOverlay")

                .classList.add("hidden");

            nextFloor();

        },

        function(error){

        }

    );

}

function startQrScanner(expectedFloor){

    if(qrReader){

        qrReader.clear();

    }

    qrReader = new Html5Qrcode("qr-reader");

    qrReader.start(

        {

            facingMode:"environment"

        },

        {

            fps:10,

            qrbox:220

        },

        function(decodedText){

            if(!decodedText.startsWith("FLOOR:")){

                return;

            }

            let scannedFloor=

            parseInt(

                decodedText.replace("FLOOR:","")

            );

            if(scannedFloor!=expectedFloor){

                alert(

                    "Wrong QR Code.\n\nExpected Floor "

                    +expectedFloor

                );

                return;

            }

            qrReader.stop();

            qrReader.clear();

            qrReader=null;

            document

            .getElementById("transitionOverlay")

            .classList.add("hidden");

            nextFloor();

        },

        function(error){

        }

    );

}