<?php require( "config.php" ); ?>
<!doctype html>
<html>
<head>
<title><?php echo $text->title ?></title>
<meta charset="utf-8" />
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet" />
<style>
body, html { margin: 0; padding:0; width: 100%; height: 100%; }
#inauguration-map {
    position: relative;
    width: 100%;
    height: 500px;
}
h1, content p { font-family: 'Raleway', sans-serif; font-weight: normal; }
content, intro { display: block }
content { }
intro {
    padding: 1em;
    max-width:800px;
    margin: auto;
}

</style>
<script type='text/javascript' src='//www.bing.com/api/maps/mapcontrol?callback=GetMap' async defer></script>
</head>
<body>
    <content>
        <intro>
            <h1><?php echo $text->title ?></h1>
            <div id="inauguration-map"></div>
<script type="text/javascript">
var map;
function GetMap() {
    map = new Microsoft.Maps.Map('#inauguration-map', {
        credentials: '<?php echo $key ?>',
        center: new Microsoft.Maps.Location(38.8896264423292, -77.01021573212567),
        zoom: 16
    });
}
</script>
        </intro>
    </content>
</body>
</html>
