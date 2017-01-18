<!doctype html>
<html>
<head>
    <title>Login</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet" />
    <link href="all.css?ver=<?php echo $version ?>" rel="stylesheet" />
    <link href="admin.css?ver=<?php echo $version ?>" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body>

    <content>
        <intro>
            <logo></logo>
            <h1><img src="http://www.voanews.com/Content/responsive/VOA/en-US/img/logo.png" height="48" /><?php echo $text->title ?></h1>
            <text>
                <p>Admin Login</p>
            </text>

            <div>
                <p>Enter Your Edit Key:</p>
                <p><input class="fontly" name="ie-pwd" autocomplete="off" type="text" id="ie-pwd" /></p>
                <p><button id="save">Login</button></p>

                <div class="fontly" id="login-response"></div>
            </div>
        </intro>
    </content>

<script>
$("#save").click(function() {
    $("#login-response").html("");
    $.ajax({
        url: "admin.php",
        dataType: "json",
        method: "post",
        data: {
            iepwd: $("#ie-pwd").val()
        },
        success: function(e) {
            $("#login-response").html(e.message);
            if( e.status == "good" ) {
                localStorage.setItem("inauguration-map-edit-key", $("#ie-pwd").val() );
                $("#login-response").append("<p>Good! You may now proceed to <a href='<?php echo $url ?>/'>editing</a>.</p>");
            } else {
                localStorage.removeItem("inauguration-map-edit-key");
            }
        }
    });
});
</script>
</body>
</html>
