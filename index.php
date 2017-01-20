<?php require_once( "config.php" ); ?>
<!doctype html>
<html>
<head>
    <title><?php echo $text->title ?></title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="canonical" href="<?php echo $url ?>" />
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet" />
    <link href="lib/nouislider.min.css" rel="stylesheet" />
    <link href="all.css?ver=<?php echo $version ?>" rel="stylesheet" />
    <link href="admin.css?ver=<?php echo $version ?>" rel="stylesheet" />

    <meta name="description" content="<?php echo $text->tweet ?>" />

    <meta property="og:title" content="<?php echo $text->title ?>" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="<?php echo $url ?>" />
    <meta property="og:image" content="<?php echo $url ?>/img/inauguration-experience.jpg" />

    <meta property="twitter:card" content="summary_large_image" />
	<meta name="twitter:site" content="@voanews" />
	<meta name="twitter:creator" content="@pp19dd" />
	<meta property="twitter:title" content="<?php echo $text->title ?>" />
	<meta property="twitter:description" content="<?php echo $text->tweet ?>" />
	<meta property="twitter:image" content="<?php echo $url ?>/img/inauguration-experience.jpg" />
    <meta name="twitter:url" content="<?php echo $url ?>/" />


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="lib/nouislider.min.js?ver=<?php echo $version ?>"></script>
    <script type="text/javascript" src="//www.bing.com/api/maps/mapcontrol?callback=GetMap" async defer></script>

<?php if( !defined("DEV") ) { ?>

<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-62930133-8', 'auto');
ga('send', 'pageview');
</script>

<?php } ?>

</head>
<body>
<script type="text/javascript">
var utag_data = {
    entity:"VOA",
    language:"English",
    language_service:"VOA English",
    short_language_service:"ENG",
    property_id:"600",
    platform:"Responsive",
    platform_short:"R",
    runs_js:"Yes",
    section:"Special Projects",
    english_section:"special-projects",
    page_title:"<?php echo $text->title ?>",
    page_type:"interactive",
    page_name:"<?php echo $text->title ?>",
    short_headline:"<?php echo $text->title ?>",
    long_headline:"<?php echo $text->title ?>",
    headline:"<?php echo $text->title ?>",
    content_type:"interactive",
    pub_year:"2017",
    pub_month:"01",
    pub_day:"20",
    pub_weekday:"Friday",
    byline:"VOA News",
    slug:"inauguration-experience"
}
</script>

<?php if( !defined("DEV") ) { ?>

<script type="text/javascript">
(function(a,b,c,d){
a='//tags.tiqcdn.com/utag/bbg/voa-nonpangea/prod/utag.js';
b=document;c='script';d=b.createElement(c);d.src=a;d.type='text/java'+c;d.async=true;
a=b.getElementsByTagName(c)[0];a.parentNode.insertBefore(d,a);
})();
</script>

<?php } ?>

    <div class="wrapper">
        <header>
            <div id="voa-logo"><a href=""><img src="http://projects.voanews.com/inaugural-comparison/img/icons/icon__logo__voa--f8f8f8.png" width="59" height="25" /></a></div>
            <h1><a href=""><?php echo $text->title ?></a></h1>
            <div id="menuButton" class="header-icon"></div>
            <nav id="main-nav" role="navigation" class="main-menu-nav">
                <ul id="main-menu">
                    <li id="comparison"><a href="http://projects.voanews.com/inaugural-comparison/">Comparing the speeches</a></li>
                    <!--<li id="bingo"><a href="http://projects.voanews.com/trump-bingo/voa.html">Bingo</a></li>-->
                    <li id="map"><a href="http://projects.voanews.com/inauguration-experience/">Map</a></li>

                    <li><a id="shareTwitter" href="javascript:void(0);"><span class="header-icon"></span><span class="social-share-text">Share on Twitter</span></a>
                    </li><li><a id="shareFacebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($url . '/'); ?>"><span class="header-icon"></span><span class="social-share-text">Share on Facebook</span></a>
                </li></ul>
            </nav>
        </header>
    </div>

    <img class="bunting" src="http://projects.voanews.com/inaugural-comparison/img/bunting.jpg" />

    <content>
        <intro id="map-experience">
            <!-- <logo></logo>
            <h1><img src="http://www.voanews.com/Content/responsive/VOA/en-US/img/logo.png" height="48" /><?php echo $text->title ?></h1> -->
            <text>
                <?php echo $text->intro ?>
            </text>
            <range class="range">
                <div class="fontly range_label" id="ranger_left"><?php echo $text->rewinder ?></div>
                <div id="ranger"></div>
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
                <table class="because" cellpadding="0" cellspacing="0">
                    <tr>
                        <th class="unseen count_top seen_twitter"><span class="count-label">Twitter</span></th>
                        <th class="unseen count_top seen_instagram"><span class="count-label">Instagram</span></th>
                        <th class="unseen count_top seen_youtube"><span class="count-label">YouTube</span></th>
                        <th class="unseen count_top seen_soundcloud"><span class="count-label">Soundcloud</span></th>
                        <th class="unseen count_top seen_facebook"><span class="count-label">Facebook</span></th>
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

    <footer>
    </footer>

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
<script src="admin.js?ver=<?php echo $version ?>"></script>

<script type="text/javascript">
    $( document ).ready(function() {
        $( '#main-nav' ).hide();

        $( '#menuButton' ).click( function() {
            $( '#main-nav' ).toggle();
        });

        $('#shareTwitter').click(function(){
    		var url = "<?php echo $url ?>/";
    		var text = <?php echo json_encode($text->tweet) ?>;
    		window.open('http://twitter.com/share?url='+encodeURIComponent(url)+'&text='+encodeURIComponent(text), '', 'left=0,top=0,width=550,height=450,personalbar=0,toolbar=0,scrollbars=0,resizable=0');
    	})

    });
</script>

</body>
</html>
