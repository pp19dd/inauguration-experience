var new_point;

$(document).ready(function() {

    var node_admin = $(
        "<admin class='fontly'></admin>"
    );

    $("range").after( node_admin );

    var b_hide = $("<button>Hide Finished Points</button>");
    var b_add = $("<button>Add New point</button>");

    b_hide.click(function() {

        b_hide.toggleClass("admin-active");

        for( var k in rendered_pts )(function(pt) {
            // console.info( rendered_pts[k] );
            pt.setOptions({
                visible: !pt.getVisible()
            });
        })(rendered_pts[k]);
    });

    node_admin.append( b_hide );
    node_admin.append( b_add );
});
