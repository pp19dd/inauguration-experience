var new_point = null;

$(document).ready(function() {

    var node_admin = $(
        "<admin class='fontly'></admin>"
    );

    $("range").after( node_admin );

    var b_hide = $("<button>Hide Finished Points</button>");
    var b_add = $("<button>Add New point</button>");
    var b_pwd = $("<div>Edit Password: <input id='pwd' type='text' style='width:300px' autocomplete='off' spellcheck='no'></div>");

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
            draggable: true
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
            "</div>"
        );

        var b_preview = $("<button id='preview'>Preview</button>");
        var b_save = $("<button id='save'>Save</button>");

        b_preview.click(function() {
            var code = $("#code").val();
            $("#preview-block").html( code );
        });

        b_save.click(function() {
            $.ajax({
                url: "admin.php",
                dataType: "json",
                method: "post",
                data: {
                    pwd: $("#pwd").val(),
                    lat: new_point.getLocation().latitude,
                    lng: new_point.getLocation().longitude,
                    code: $("#code").val()
                },
                success: function(e) {
                    //console.info( e );
                    // assume success

                    $("#preview-block").html("");
                    $("#code").val("");
                    map.entities.remove(new_point);
                    new_point = null;
                }
            })
        });

        $(".editing").append(b_preview);
        $(".editing").append(b_save);

    });

    node_admin.append( b_hide );
    node_admin.append( b_add );
    node_admin.append( b_pwd );
});
