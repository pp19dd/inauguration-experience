var map;

function GetMap() {
    map = new Microsoft.Maps.Map(config.map_id, {
        credentials: config.key,
        center: new Microsoft.Maps.Location(config.center.lat, config.center.lng),
        zoom: config.center.zoom
    });

    update_data();
}

function recompute_slider() {
    $("#ranger").attr("max", rendered_pts.length);
    $("#ranger").rangeslider("update", true);
}

$(document).ready(function() {
    $("#ranger").rangeslider({
        polyfill: false,

        // Default CSS classes
        rangeClass: 'rangeslider',
        disabledClass: 'rangeslider--disabled',
        horizontalClass: 'rangeslider--horizontal',
        verticalClass: 'rangeslider--vertical',
        fillClass: 'rangeslider__fill',
        handleClass: 'rangeslider__handle',
        onSlide: function(position, value) {
            map_show_points(value);
        }
    });
});


var rendered_ids = {};
var rendered_pts = [];

function render_point_shout( pt ) {
    // console.info( pt );
}

function render_overlay( pt, pushpin ) {
    $("#embed").html( pt.embed );
    // console.info( pt );
}

function map_show_points( cutoff ) {
    for( var i = 0; i < rendered_pts.length; i++ ) {
        rendered_pts[i].setOptions( { visible: (i < cutoff ? true : false) });
    }
}

function render_point_map( pt ) {
    try {
        var pushpin = new Microsoft.Maps.Pushpin(
            new Microsoft.Maps.Location(pt.lat, pt.lng), null
        );
        Microsoft.Maps.Events.addHandler(pushpin, 'click', function() {
            render_overlay( pt, pushpin );
        });
        map.entities.push(pushpin);
        rendered_pts.push( pushpin );
    } catch( e ) {
    }
}

// delegates to display routines
function render_point( pt ) {
    if( typeof rendered_ids[pt.id] != "undefined" ) {
        // point exists, don't duplicate
        return;
    } else {
        rendered_ids[pt.id] = 1;
    }

    // shout
    if( pt.destination == "Bottom" ) {
        render_point_shout(pt);
    } else {
        render_point_map(pt);
    }
}

// can be used for data updates
function update_data() {
    $.ajax({
        type: "POST",
        url: "data.php",
        dataType: "json",
        success: function(e) {
            for( var i = 0; i < e.length; i++ )(function(pt) {
                render_point( pt );
            })(e[i]);

            recompute_slider();
        }
    })
}
