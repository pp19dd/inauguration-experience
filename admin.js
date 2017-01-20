var new_point = null;

$(document).ready(function() {
    var edit_key = localStorage.getItem("inauguration-map-edit-key");
    if( edit_key === null ) return;

    do_admin_setup();
});

function do_update_time(id) {
    var timestamp = $("#edit_timestamp").val();

    var reply = confirm("Do you want to update time for point # " + id + " ?");
    if( !reply ) return;

    $.ajax({
        url: "admin.php",
        dataType: "json",
        method: "post",
        data: {
            action: 'time',
            pwd: localStorage.getItem("inauguration-map-edit-key"),
            point_id: id,
            time: timestamp
        },
        success: function(e) {
            $("#embed_inner").html(e.message);
        }
    })

}

function do_delete(id) {
    var reply = confirm("Do you want to delete point # " + id + " ?");
    if( !reply ) return;

    $.ajax({
        url: "admin.php",
        dataType: "json",
        method: "post",
        data: {
            action: 'delete',
            pwd: localStorage.getItem("inauguration-map-edit-key"),
            point_id: id
        },
        success: function(e) {
            $("#embed_inner").html(e.message);
        }
    })
}

function do_admin_setup() {

    var node_admin = $(
        "<admin class='fontly'></admin>"
    );

    $("range").after( node_admin );

    var b_hide = $("<button>Hide Finished Points</button>");
    var b_add = $("<button>Add New point</button>");

    b_hide.click(function() {
        b_hide.toggleClass("admin-active");

        for( var k in rendered_pts )(function(pt) {
            pt.setOptions({
                visible: !pt.getVisible()
            });
        })(rendered_pts[k]);
    });

    b_add.click(function() {
        if( new_point !== null ) return;

        new_point = new Microsoft.Maps.Pushpin(map.getCenter());
        new_point.setOptions({
            draggable: true,
            icon: "img/map-new.png",
            width: 32,
            height: 37,
            anchor: new Microsoft.Maps.Point(15, 34)
        });

        Microsoft.Maps.Events.addHandler(new_point, 'drag', function(x) {
            var p = new_point.getLocation();
            $("#lat").html( p.latitude.toPrecision(6) );
            $("#lng").html( p.longitude.toPrecision(6) );
        });

        map.entities.push( new_point );

        $("#embed_inner").html(
            "<div class='editing'>" +
            "<div id='preview-block'></div>" +
            "<p>Click and drag point to set position.</p>" +
            "<p>Lat: <span id='lat'>0</span>, lng: <span id='lng'>0</span>" +
            "<p>Embed code (instagram / fb / twitter / etc.):</p>" +
            "<textarea id='code'></textarea>" +
            "<p>Time: " +
            "<input id='create-timestamp' type='text' autocomplete='off' />" +
            "</p><p>Format ex: 9:40 am.  Leave blank for 'now'.</p>" +
            "</div>"
        );

        var b_preview = $("<button id='preview'>Preview</button>");
        var b_save = $("<button id='save'>Save</button>");

        b_preview.click(function() {
            var code = $("#code").val();
            $("#preview-block").removeClass("error");
            $("#preview-block").html( code );

            try {
                instgrm.Embeds.process();
            } catch( e ) {

            }
        });

        b_save.click(function() {
            $.ajax({
                url: "admin.php",
                dataType: "json",
                method: "post",
                data: {
                    pwd: localStorage.getItem("inauguration-map-edit-key"),
                    lat: new_point.getLocation().latitude,
                    lng: new_point.getLocation().longitude,
                    time: $("#create-timestamp").val(),
                    code: $("#code").val()
                },
                success: function(e) {
                    $("#preview-block").html(e.message);

                    if( e.status === "good" ) {
                        map.entities.remove(new_point);
                        new_point = null;
                        $("#preview-block").removeClass("error");
                        $("#preview-block").addClass("everything-ok");
                        $("#code, #save, #preview").remove();

                        update_data();
                    } else {
                        $("#preview-block").addClass("error");
                        $("#preview-block").removeClass("everything-ok");
                    }
                }
            })
        });

        $(".editing").append(b_preview);
        $(".editing").append(b_save);

    });

    node_admin.append( b_hide );
    node_admin.append( b_add );
}
