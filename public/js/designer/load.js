function loadDesigner(){

    fetch("/admin/designer/"+FLOOR_ID+"/load")

    .then(response=>response.json())

    .then(data=>{

        hallways=[];

        locations=[];

        waypoints=[];

        connections=[];


        /*
        ------------------------
        Hallways
        ------------------------
        */

        data.hallways.forEach(function(h){

            hallways.push({

                x1:h.x1,

                y1:h.y1,

                x2:h.x2,

                y2:h.y2

            });

        });


        /*
        ------------------------
        Locations
        ------------------------
        */

        data.locations.forEach(function(l){

            locations.push({

                id:l.id,

                name:l.name,

                x:l.x,

                y:l.y

            });

        });


        /*
        ------------------------
        Waypoints
        ------------------------
        */

        data.waypoints.forEach(function(w){

            waypoints.push({

                id:w.id,

                x:w.x,

                y:w.y

            });

        });


        /*
        ------------------------
        Connections
        ------------------------
        */

        data.connections.forEach(function(c){

            let from=waypoints.find(w=>w.id==c.from_waypoint_id);

            let to=waypoints.find(w=>w.id==c.to_waypoint_id);

            if(!from || !to) return;

            connections.push({

                from:from.id,

                to:to.id,

                distance:c.distance

            });

        });


        refreshLocationList();

        redraw();

    });

}