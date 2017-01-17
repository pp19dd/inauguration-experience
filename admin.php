<?php

$ret = array();

$ret["input_p"] = $_POST;
$ret["input_g"] = $_GET;
$ret["time"] = time();

echo json_encode($ret);
