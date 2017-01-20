var map;
var slider;
var storage_prefix = "inauguration-map-";
var data_global = null;
var first_render = false;

// synchronized at each data update
var time_lookup = {
    from: [],
    to: []
}

var rendered_ids = {};
var rendered_pts = [];
var counts = {
    seen: {
        twitter: 0,
        facebook: 0,
        instagram: 0,
        soundcloud: 0,
        youtube: 0,
        total: 0
    },
    available: {
        twitter: 0,
        facebook: 0,
        instagram: 0,
        soundcloud: 0,
        youtube: 0,
        total: 0
    }
};

function GetMap() {
    map = new Microsoft.Maps.Map(config.map_id, {
        credentials: config.key,
        center: new Microsoft.Maps.Location(config.center.lat, config.center.lng),
        zoom: config.center.zoom,
        showMapTypeSelector: false,
        disableScrollWheelZoom: true
    });

    update_data();
}

function recompute_slider() {
    // $("#ranger").attr("max", rendered_pts.length);
    // $("#ranger").rangeslider("update", true);
    var slider_max = rendered_pts.length - 1;
    if( slider_max === 0 ) slider_max = 1;

    try {
        slider.noUiSlider.updateOptions({
            range: { min: 0, max: slider_max }
        });
        slider.noUiSlider.set([null, rendered_pts.length]);
    } catch( e ) {

    }
}

function init_slider() {
    try {
        slider = $("#ranger")[0];
    } catch( e ) {
        return;
    }

    noUiSlider.create(slider, {
        start: [ 0, rendered_pts.length ],
        tooltips: [true, true],
        xformat: {
            to: function(v) { // encode
                if( typeof time_lookup.to[parseInt(v)] == "undefined" ) {
                    return(
                        "Now<span class='hidden-tooltip-data'>" + v + "</span>"
                    );
                }
                return(
                    time_lookup.to[parseInt(v)] + "<span class='hidden-tooltip-data'>" + v + "</span>"
                );
                //return(  );
                // return( "a_" + (v * 3) );
                for( var i = 0; i < data_global.length; i++ ) {
                    if( parseInt(v) === i ) {
                        return( data_global[i].stamp_english );
                    }
                }
            },
            from: function(v) { // decode
                //return( time_lookup.to[parseInt(v)] );
                var x = v.split(":");
                return( x[0] );
                // return( parseFloat(x[1]) / 3 );
                //var x = v.split(":");
                return( x[0] );
                for( var i = 0; i < data_global.length; i++ ) {
                    if( parseInt(v) == data_global[i].stamp_english ) {
                        return( i );
                    }
                }
            }
        },
        behaviour: 'drag',
        connect: true,
        range: {
            'min': [ 0 ],
            'max': [ 10 ]
        }
    });

    slider.noUiSlider.on("update", function(r) {
        var a = parseInt(r[0]);
        var b = parseInt(r[1]);

        var label_a = time_lookup.to[a];
        var label_b = time_lookup.to[b];

        if( b === time_lookup.to.length - 1 ) label_b = "Now";

        if( label_a === "NaN" ) label_a = " ";
        if( label_b === "NaN" ) label_b = " ";

        // this is the weirdest hack i've had to use in awhile
        $(".noUi-handle:eq(0) .noUi-tooltip").html( label_a );
        $(".noUi-handle:eq(1) .noUi-tooltip").html( label_b );
        map_show_points(a, b);
    });
}

$(document).ready(function() {
    $("#reset_link a").click(function() {
        do_clear();
        render_counts();

        try {
            ga('send', 'event', {
                eventCategory: 'Map',
                eventAction: "Reset Click",
                eventLabel: 'Done',
                eventValue: 1
            });
        } catch( e ) {

        }

        return( false );
    });
});

function render_counts() {
    for( var k in counts.seen )(function(key) {
        var node_bottom = $(".count_bottom.seen_" + key);
        var node_top = $(".count_top.seen_" + key);

        node_bottom.html(
            counts.seen[key] + "/" + counts.available[key]
        );

        if( counts.seen[key] === 0 ) {
            node_bottom.addClass("unseen");
            node_top.addClass("unseen");
        } else {
            node_bottom.removeClass("unseen");
            node_top.removeClass("unseen");
        }
    })(k);
}

function render_point_shout( pt ) {

}

function render_overlay( pt, pushpin ) {
    var html_before = "";

    // add delete button, if needed
    var edit_key = localStorage.getItem("inauguration-map-edit-key");
    if( edit_key !== null ) {
        html_before = "<button class='delete-button' onclick='do_delete(" + pt.id + ")'>Delete pt # " + pt.id + "</button><br/>";
    }

    $("#embed").removeClass("unused");
    $("#embed_inner").html( html_before + pt.embed );
}

function map_show_points( start, cutoff ) {
    for( var i = 0; i < rendered_pts.length; i++ ) {
        var is_visible = true;
        if( i < start ) is_visible = false;
        if( i > cutoff ) is_visible = false;
        rendered_pts[i].setOptions( { visible: is_visible });
    }
}

function do_clear() {
    // reset stored keys
    for( var k in localStorage )(function(key) {
        if( key.indexOf(storage_prefix + "seen_") === 0 ) {
            localStorage.removeItem(key);
        }
    })(k);

    // reset internal counter
    for( var k in counts.seen )(function(key) {
        counts.seen[k] = 0;
    })(k);

    // and map point icons
    for( var i = 0; i < rendered_pts.length; i++ ) {
        var icon = rendered_pts[i].getIcon();
        var pos = icon.indexOf(".seen.png");

        if( pos !== -1 ) {
            var new_icon = icon.substr(0,pos);
            rendered_pts[i].setOptions({ icon: new_icon });
        }
    }

    // and the embed hint
    $("#embed_inner").html(
        '<p class="instructions">' + text.instructions + '</p>'
    );
    $("#embed").addClass("unused");
}

function do_seen(pt, pushpin) {
    localStorage.setItem(storage_prefix + "seen_" + pt.id, "yes");
    pushpin.setOptions({ icon: pushpin.getIcon() + ".seen.png"});
    counts.seen[pt.provider]++;
    counts.seen.total++;
    render_counts();
}

function render_point_map( pt ) {
    var pushpin = new Microsoft.Maps.Pushpin(
        new Microsoft.Maps.Location(pt.lat, pt.lng), {
            icon: "img/map-" + pt.provider + ".png",
            width: 32,
            height: 37,
            anchor: new Microsoft.Maps.Point(15, 34)
        }
    );

    var x = localStorage.getItem(storage_prefix + "seen_" + pt.id);
    if( x == "yes" ) {
        do_seen(pt, pushpin);
    }

    counts.available[pt.provider]++;
    counts.available[pt.total]++;

    Microsoft.Maps.Events.addHandler(pushpin, 'click', function() {
        var current_icon = pushpin.getIcon();
        if( current_icon.indexOf(".seen.png") === -1 ) {
            do_seen(pt, pushpin);
        }
        render_overlay( pt, pushpin );

        try {
            ga('send', 'event', {
                eventCategory: 'Map',
                eventAction: pt.provider + " click",
                eventLabel: 'Point # ' + pt.id,
                eventValue: 1
            });
        } catch( e ) {

        }
    });
    map.entities.push(pushpin);
    rendered_pts.push( pushpin );
}

// delegates to display routines
function render_point( pt ) {
    if( typeof rendered_ids[pt.id] != "undefined" ) {
        // point exists, don't duplicate

        // however, hm, it might be deleted, so lets check it.

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
            data_global = [];
            time_lookup = { from: [], to: [] };

            for( var i = 0; i < e.length; i++ )(function(pt) {
                data_global.push(pt);

                time_lookup.from.push(i);
                time_lookup.to.push(pt.stamp_english);

                render_point( pt );
            })(e[i]);

            if( first_render === false && e.length > 0 ) {
                first_render = true;
                init_slider();
            } else {
                $("p.instructions").html( text.notyet );
            }

            recompute_slider();
            render_counts();
        }
    })
}
