<?php
require_once( "config.php" );

/*
last inauguration breakdown:

    top                     bottom
    -------------------     ------
    instagram       102
    youtube          19
    twitter          25     27
    soundcloud        4

    total           150     27

    combined total: 176

    string length of all ids: 459 bytes, within cookie size range
*/

$data = json_decode(file_get_contents($data_file));
$clean_list = array();

foreach( $data->list as $k => $v ) {
    if( $v->is_deleted == "No" ) {
        $clean_list[] = $v;
    }
}

// sort by timestamp
function bystamp($a, $b) {
    $p1 = strtotime($a->stamp);
    $p2 = strtotime($b->stamp);
    if( $p1 > $p2 ) return( true );
    return( false );
}
usort($clean_list, "bystamp");

echo json_encode( $clean_list );

#echo json_encode( $data->list );

/*
[0] => stdClass Object
    (
        [id] => 27
        [is_deleted] => No
        [destination] => Bottom
        [provider] => twitter
        [lat] =>
        [lng] =>
        [url] => http://twitter.com/andreanbyan/status/293170553240580097
        [embed] => (code...)
    )
*/
