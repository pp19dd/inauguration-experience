<?php require( "config.php" ); ?>
<!doctype html>
<html>
<head>
<title><?php echo $text->title ?></title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet" />
    <link href="lib/rangeslider.css" rel="stylesheet" />
    <link href="all.css?ver=<?php echo $version ?>" rel="stylesheet" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="lib/rangeslider.js?ver=<?php echo $version ?>"></script>
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
            <range>
                <div class="fontly" id="ranger_left">a</div>
                <input type="range" id="ranger" min="0" max="10" step="1" value="0" />
                <div class="fontly" id="ranger_right">b</div>
            </range>
            <div id="map-row">
                <div id="embed"></div>
                <div id="inauguration-map"></div>
            </div>
            <text>
                <?php echo $text->shout ?>
            </text>
            <shout>
                <img src="https://gdb.voanews.com/74C23C67-415C-47C7-8910-3E9427C8DBF3_w1280.jpg" />
            </shout>
        </intro>
    </content>

<script type="text/javascript">
var config = {
    url: "<?php echo $url ?>",
    key: "<?php echo $key ?>",
    map_id: "#inauguration-map",
    center: {
        lat: 38.89243580574502,
        lng: -77.02337440371517,
        zoom: 15
    }
}
</script>
<script src="main.js?ver=<?php echo $version ?>"></script>
</body>
</html>
