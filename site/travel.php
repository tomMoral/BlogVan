<?php
include("headerPHP.php"); //les post sont enregistré avec notre horloge, donc heure USA
htmlHeader("travel");

$date = strtotime("August 1, 2013 9:00 AM");
$remaining = $date - time();
$months_remaining = floor($remaining / 2678400);
$days_remaining = floor(($remaining % 2678400) / 86400);
?>
<div class="center">
    <img src="../images/funnybus.png" width ="500px" margin-botton="-50px"/>
    <h1>The adventure begins in <?php
        if ($months_remaining) {
            echo "$months_remaining months, $days_remaining days";
        } else {
            echo "$days_remaining days";
        }
        ?>.<br/>Get ready!
    </h1>
</div>