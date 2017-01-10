<?php require( "config.php" ); ?>
<!doctype html>
<html>
<head>
<title><?php echo $text->title ?></title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet" />
    <link href="lib/nouislider.min.css" rel="stylesheet" />
    <link href="all.css?ver=<?php echo $version ?>" rel="stylesheet" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="lib/nouislider.min.js?ver=<?php echo $version ?>"></script>
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
                <div class="fontly range_label" id="ranger_left"><?php echo $text->rewinder ?></div>
                <div id="ranger"></div>
                <div class="fontly range_label" id="ranger_right"><?php echo date("h:i A") ?></div>
            </range>
            <div id="map-row">
                <div id="embed">
                    <div id="embed_inner"></div>
                </div>
                <div id="inauguration-map"></div>
            </div>
            <p>
                <span><?php echo $text->counts_all ?></span>
                <span class="seen_label seen_twitter">Twitter: </span><span class="seen_count seen_twitter">0</span>
                <span class="seen_label seen_instagram">Instagram: </span><span class="seen_count seen_instagram">0</span>
                <span class="seen_label seen_youtube">Youtube: </span><span class="seen_count seen_youtube">0</span>
                <span class="seen_label seen_soundcloud">Soundcloud: </span><span class="seen_count seen_soundcloud">0</span>
                <span class="seen_label seen_facebook">Facebook: </span><span class="seen_count seen_facebook">0</span>
                <!--<span class="seen_label seen_total"><?php echo $text->counts_total ?>: </span><span class="seen_count seen_total">0</span>-->
            </p>
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
