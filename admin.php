<?php
require( "config.php" );
$ret = array();

// login form
if( empty($_POST) ) {
    include("admin-login.php");
    die;
}

// login
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

// edits go here
$ret["input_p"] = $_POST;
$ret["input_g"] = $_GET;
$ret["time"] = time();

echo json_encode($ret);
