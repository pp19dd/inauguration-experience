var map;
var slider;

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
    slider.noUiSlider.updateOptions({
        range: { min: 0, max: rendered_pts.length }
    });
    slider.noUiSlider.set([null, rendered_pts.length]);
}

$(document).ready(function() {
    /*
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
    */
    slider = $("#ranger")[0];

    noUiSlider.create(slider, {
    	start: [ 0, 10 ],
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
        $(".seen_count.seen_" + key).html(
            counts.seen[key] + "/" + counts.available[key]
        );
    })(k);
    // $("#seen_" + pt.provider).html( seen[pt.provider] );
    // $("#seen_total").html( seen.total );

}

function render_point_shout( pt ) {
    // console.info( pt );
}

function render_overlay( pt, pushpin ) {
    $("#embed_inner").html( pt.embed );
    // console.info( pt );
}

function map_show_points( start, cutoff ) {
    console.info( start, cutoff );
    for( var i = 0; i < rendered_pts.length; i++ ) {
        var is_visible = true;
        if( i < start ) is_visible = false;
        if( i > cutoff ) is_visible = false;
        rendered_pts[i].setOptions( { visible: is_visible });
    }
}

function do_clear() {
    for( var i = 0; i < localStorage.length; i++ )(function(key) {
        if( key.indexOf("seen_") === 0 ) {
            localStorage.removeItem(key);
        }
    })(localStorage.key(i));
}

function do_seen(pt, pushpin) {
    localStorage.setItem("seen_" + pt.id, "yes");
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
            height: 37
        }
    );

    var x = localStorage.getItem("seen_" + pt.id);
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
                render_point( pt );
            })(e[i]);

            recompute_slider();
            render_counts();
        }
    })
}
