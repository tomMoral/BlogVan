<?php

include_once("initSession.php");
if (!isset($_SESSION['language'])) {
    $default_country = "EN";
//get the user location
    $content = @file_get_contents('http://api.hostip.info/get_html.php?ip=');
    if ($content) {
        $ex = explode(" ", $content);
        $country = $ex[1];
        if ($country == "FRANCE") {
            $default_country = "FR";
        }
    }
    $_SESSION['language'] =  $default_country;
}
?>
