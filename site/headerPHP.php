<?php

include_once("initSession.php");

include_once("class/database.php");
include_once('class/post.php');
include_once('class/user.php');
include_once("class/photos.php");
include_once('class/comments.php');
include_once('utils.php');


$default_country = "US";
//get the user location
$content = @file_get_contents('http://api.hostip.info/get_html.php?ip=');
if ($content) {
    $country = explode(" ", $content)[1];
    if ($country == "FRANCE") {
        $default_country = "FR";
    }
}

date_default_timezone_set('America/Los_Angeles');
?>