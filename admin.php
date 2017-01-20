<?php
require_once( "config.php" );
$ret = array();

// ---------------------------------------------------------------------------
// login form
// ---------------------------------------------------------------------------
if( empty($_POST) ) {
    include("admin-login.php");
    die;
}

// ---------------------------------------------------------------------------
// login
// ---------------------------------------------------------------------------
if( isset( $_POST['iepwd']) ) {

    $ret["status"] = "good";

    if( $admin_password === "" ) {
        $ret["message"] = "ERROR: edit key is not setup";
        $ret["status"] = "bad";
    }
    if( $admin_password !== $_POST['iepwd'] ) {
        $ret["message"] = "ERROR: invalid edit key";
        $ret["status"] = "bad";
    }

    echo json_encode($ret);
    die;
}

// ---------------------------------------------------------------------------
// edits go here
// ---------------------------------------------------------------------------
$ret["input_p"] = $_POST;
$ret["input_g"] = $_GET;
$ret["time"] = time();

// need valid edit key
if( $_POST['pwd'] !== $admin_password ) {
    $ret["message"] = "<strong>ERROR: Invalid edit key.</strong>";
    echo json_encode($ret);
    die;
}

// nodb :/
$data = json_decode(file_get_contents($data_file), true);

// ---------------------------------------------------------------------------
// delete
// ---------------------------------------------------------------------------
if( isset( $_POST['point_id']) ) {

    $count_deleted = 0;
    foreach( $data["list"] as $k => $v ) {
        if( $v['id'] == $_POST['point_id'] ) {
            $data["list"][$k]["is_deleted"] = "Yes";
            $count_deleted++;
        }
    }

    if( $count_deleted > 0 ) {
        file_put_contents($data_file, json_encode($data));
        $ret["status"] = "good";
        $ret["message"] = "Point # " . $_POST['point_id'] . " deleted.";
    } else {
        $ret["status"] = "bad";
        $ret["message"] = "Can't delete point # " . $_POST['point_id'];
    }

    echo json_encode($ret);
    die;
}


function simple_provider_detector($code) {
    if( stripos($code, "platform.twitter.com") !== false ) return( "twitter" );
    if( stripos($code, "www.facebook.com/plugins/") !== false ) return( "facebook" );
    if( stripos($code, "platform.instagram.com") !== false ) return( "instagram" );
    if( stripos($code, "https://www.youtube.com") !== false ) return( "youtube" );
    if( stripos($code, "https://w.soundcloud.com/") !== false ) return( "soundcloud" );
    return( "unknown" );
}

function get_new_id($list) {
    if( empty($list) ) return( 1 );

    $ids = array();
    foreach( $list as $v ) {
        $ids[] = $v["id"];
    }
    return(max($ids) + 1);
}

// new object
$new = array(
    "id" => get_new_id($data["list"]),
    "is_deleted" => "No",
    "destination" => "Top",
    "provider" => simple_provider_detector($_POST['code']),
    "lat" => $_POST["lat"],
    "lng" => $_POST["lng"],
    "url" => "",
    "embed" => $_POST["code"]
);

if( $new["provider"] === "unknown" ) {
    $ret["status"] = "bad";
    $ret["message"] =
        "ERROR: can't save this because it doesn't look like valid embed " .
        " code.   Please check it and make sure it previews.  <br/><br/>" .
        "You can only use official twitter, instagram, youtube, soundcloud " .
        "or facebook code.";

    echo json_encode($ret);
    die;
} else {
    $ret["message"] = "Added " . $new["provider"] . " to map.";
}

// everything fine, lets save it
$data["list"][] = $new;
file_put_contents($data_file, json_encode($data));
$ret["status"] = "good";

echo json_encode($ret);
die;
