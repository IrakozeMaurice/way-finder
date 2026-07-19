function loadDesigner(){

    fetch("/admin/designer/"+FLOOR_ID+"/load")

    .then(response=>response.json())

    .then(data=>{

        hallways=[];
        locations=[];
        waypoints=[];
        connections=[];

        /*
        -----------------------------------
        Hallways
        -----------------------------------
        */

        data.hallways.forEach(function(h){

            hallways.push({

                id:h.id,
                db_id:h.id,

                x1:h.x1,
                y1:h.y1,
                x2:h.x2,
                y2:h.y2

            });

        });


        /*
        -----------------------------------
        Locations
        -----------------------------------
        */

        data.locations.forEach(function(l){

            locations.push({

                id:l.id,
                db_id:l.id,

                name:l.name,

                x:l.x,
                y:l.y,

                waypoint_id:l.waypoint_id

            });

        });


        /*
        -----------------------------------
        Waypoints
        -----------------------------------
        */

        data.waypoints.forEach(function(w){

            waypoints.push({

                id:w.id,
                db_id:w.id,

                x:w.x,
                y:w.y,

                floor_id:w.floor_id,

                is_transition:w.is_transition,

                linked_waypoint_id:w.linked_waypoint_id

            });

        });


        /*
        -----------------------------------
        Connections
        -----------------------------------
        */

        data.connections.forEach(function(c){

            let from=waypoints.find(function(w){

                return w.id==c.from_waypoint_id;

            });

            let to=waypoints.find(function(w){

                return w.id==c.to_waypoint_id;

            });

            if(!from || !to){

                return;

            }

            connections.push({

                id:c.id,
                db_id:c.id,

                from:from.id,

                to:to.id,

                distance:c.distance

            });

        });


        refreshLocationList();

        redraw();

    });

}