function saveDesigner() {

    const token = document
        .querySelector('meta[name="csrf-token"]');

    if (!token) {

        alert("CSRF token not found.");

        return;

    }

    fetch("/admin/designer/" + FLOOR_ID + "/save", {

        method: "POST",

        headers: {

            "Content-Type": "application/json",

            "X-CSRF-TOKEN": token.content

        },

        body: JSON.stringify({

            hallways: hallways,

            locations: locations,

            waypoints: waypoints,

            connections: connections

        })

    })

    .then(response => response.json())

    .then(data => {

        if(data.success){

            alert(data.message);

        }else{

            alert(data.message);

        }

    })

    .catch(error => {

        console.error(error);

        alert("Save failed.");

    });

}