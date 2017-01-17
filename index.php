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
    <link href="admin.css?ver=<?php echo $version ?>" rel="stylesheet" />

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
                <div id="inauguration-map"></div>
                <div id="embed" class="unused">
                    <div id="embed_inner" class="fontly">
                        <p class="instructions"><?php echo $text->instructions ?></p>
                    </div>
                </div>
            </div>
            <div class="totals fontly">
                <p><span><?php echo $text->counts_all ?></span></p>
                <table class="because" width="100%">
                    <tr>
                        <td class="unseen count_top seen_twitter">Twitter</td>
                        <td class="unseen count_top seen_instagram">Instagram</td>
                        <td class="unseen count_top seen_youtube">Youtube</td>
                        <td class="unseen count_top seen_soundcloud">Soundcloud</td>
                        <td class="unseen count_top seen_facebook">Facebook</td>
                    </tr>
                    <tr>
                        <td class="unseen count_bottom seen_twitter">0</td>
                        <td class="unseen count_bottom seen_instagram">0</td>
                        <td class="unseen count_bottom seen_youtube">0</td>
                        <td class="unseen count_bottom seen_soundcloud">0</td>
                        <td class="unseen count_bottom seen_facebook">0</td>
                    </tr>
                </table>
                <p id="reset_link"><a href="#"><?php echo $text->reset ?></a></p>
            </div>
        </intro>
    </content>

<script type="text/javascript">
var text = <?php echo json_encode($text) ?>;
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
<!--<script src="admin.js?ver=<?php echo $version ?>"></script>-->
<footer>
</footer>
</body>
</html>
