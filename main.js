var map;
var slider;
var storage_prefix = "inauguration-map-";

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
    var slider_max = rendered_pts.length;
    if( slider_max === 0 ) slider_max = 1;

    slider.noUiSlider.updateOptions({
        range: { min: 0, max: slider_max }
    });
    slider.noUiSlider.set([null, rendered_pts.length]);
}

$(document).ready(function() {
    slider = $("#ranger")[0];

    noUiSlider.create(slider, {
    	start: [ 0, 10 ],
        tooltips: [true, true],
        behaviour: 'drag',
        connect: true,
        range: {
    		'min': [ 0 ],
    		'max': [ 10 ]
    	}
    });

    slider.noUiSlider.on("update", function(r) {
        map_show_points(parseInt(r[0]), parseInt(r[1]));
    });

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
                if( pt.is_deleted === "No" ) {
                    render_point( pt );
                }
            })(e[i]);

            recompute_slider();
            render_counts();
        }
    })
}
