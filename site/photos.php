<?php

include_once("headerPHP.php"); //les post sont enregistré avec notre horloge, donc heure USA
htmlHeader("photo");
$user = user::getSessionUser();
if ($user == null || $user->type == 0) {
    $A = scandir("pics_up/A/");
    for ($i = 0; $i < count($A); $i++) {
        if (substr($A[$i], 0, 1) != ".") {
            $A[$i] = "pics_up/A/" . $A[$i];
        }
    }
    array_multisort($A, SORT_DESC);
    foreach ($A as $v) {
        if (substr($v, 0, 1) != ".") {
            echo "<img class='all_photos' src='" . $v . "'/> ";
        }
    }
} else {
    $A = scandir("pics_up/A/");
    for ($i = 0; $i < count($A); $i++) {
        if (substr($A[$i], 0, 1) != ".") {
            $A[$i] = "pics_up/A/" . $A[$i];
        }
    }
    $B = scandir("pics_up/B/");
    for ($i = 0; $i < count($B); $i++) {
        if (substr($B[$i], 0, 1) != ".") {
            $B[$i] = "pics_up/B/" . $B[$i];
        }
    }

    $C = array_merge($A, $B);
    array_multisort($C, SORT_DESC);
    foreach ($C as $v) {
        if (substr($v, 0, 1) != ".") {
            echo "<img class='all_photos' src='" . $v . "'/> ";
        }
    }
}
include_once("footer.php");
?>
