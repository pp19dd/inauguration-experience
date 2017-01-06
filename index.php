<?php require( "config.php" ); ?>
<!doctype html>
<html>
<head>
<title><?php echo $text->title ?></title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet" />
<link href="rangeSlider.min.css" rel="stylesheet" />
<script src="rangeSlider.min.js"></script>
<style>
body, html { margin: 0; padding:0; width: 100%; height: 100%; }
h1, content p { font-family: 'Raleway', sans-serif; font-weight: normal; }
content, intro { display: block }
content { }
intro {
    padding: 1em;
    max-width:1280px;
    margin: auto;
}
intro p { line-height: 1.5em; font-size: 1.2em; }

#map-row {
    margin-top: 2em;
    display: flex;
}
#embed {
    flex-grow:3;
    background-color: #DDDDDD;
}
#inauguration-map {
    flex-grow:7;
    position: relative;
    height: 500px;
    background-color: #DDDDDD;
    margin-left:1em;
}
@media (max-width:600px) {
    #map-row { flex-direction: column }
    #inauguration-map { margin-left: 0 }
    #embed { height: 300px; }
}

.rangeSlider__fill {
    background-color: #1f2ebb;
}
intro h1 img { margin-right: 1em; }
intro text { display:block; padding-top: 1em; padding-bottom:1em; }

</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script type='text/javascript' src='//www.bing.com/api/maps/mapcontrol?callback=GetMap' async defer></script>
</head>
<body>
    <content>
        <intro>
            <logo></logo>
            <h1><img src="http://www.voanews.com/Content/responsive/VOA/en-US/img/logo.png" height="48" /><?php echo $text->title ?></h1>
            <text>
                <?php echo $text->intro ?>
            </text>
            <div id="ranger"></div>
            <div id="map-row">
                <div id="embed"></div>
                <div id="inauguration-map"></div>
            </map-row>
        </intro>
    </content>

<script type="text/javascript">
var map;
function GetMap() {
    map = new Microsoft.Maps.Map('#inauguration-map', {
        credentials: '<?php echo $key ?>',
        center: new Microsoft.Maps.Location(38.8896264423292, -77.01021573212567),
        zoom: 16
    });
}

var slider = document.querySelectorAll('#ranger');
rangeSlider.create(slider, {
    polyfill: true,     // Boolean, if true, custom markup will be created
    rangeClass: 'rangeSlider',
    disabledClass: 'rangeSlider--disabled',
    fillClass: 'rangeSlider__fill',
    bufferClass: 'rangeSlider__buffer',
    handleClass: 'rangeSlider__handle',
    startEvent: ['mousedown', 'touchstart', 'pointerdown'],
    moveEvent: ['mousemove', 'touchmove', 'pointermove'],
    endEvent: ['mouseup', 'touchend', 'pointerup'],
    min: null,          // Number , 0
    max: null,          // Number, 100
    step: null,         // Number, 1
    value: null,        // Number, center of slider
    buffer: null,       // Number, in percent, 0 by default
    stick: null,        // [Number stickTo, Number stickRadius] : use it if handle should stick to stickTo-th value in stickRadius
    borderRadius: 10,    // Number, if you use buffer + border-radius in css for looks good,
    onInit: function () {
        // console.info('onInit')
    },
    onSlideStart: function (position, value) {
        // console.info('onSlideStart', 'position: ' + position, 'value: ' + value);
    },
    onSlide: function (position, value) {
        // console.log('onSlide', 'position: ' + position, 'value: ' + value);
    },
    onSlideEnd: function (position, value) {
        // console.warn('onSlideEnd', 'position: ' + position, 'value: ' + value);
    }
});

var rendered_ids = {};

function render_point( pt ) {
    rendered_ids[pt.id]++;
    console.info( pt.id );
    if( pt.destination == "bottom" ) return;

    var pushpin = new Microsoft.Maps.Pushpin(
        new Microsoft.Maps.Location(pt.lat, pt.lng), null
    );
    map.entities.push(pushpin);

    // map.entities.clear();
    // var pushpinOptions = {icon: virtualPath + '/Content/images/SpaceNeedle.jpg', width: 30, height: 50};
    //var pushpin= new Microsoft.Maps.Pushpin(map.getCenter(), pushpinOptions);
    //map.entities.push(pushpin);

}

function update_data() {
    $.ajax({
        type: "POST",
        url: "data.php",
        dataType: "json",
        success: function(e) {
            for( var i = 0; i < e.length; i++ )(function(pt) {
                if( typeof rendered_ids[pt.id] == "undefined" ) {
                    rendered_ids[pt.id] = 0;
                    render_point( pt );
                }
            })(e[i]);
        }
    })
}

update_data();

</script>

</body>
</html>
