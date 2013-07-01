<?php
include("headerPHP.php");
htmlHeader("travel");

$date = strtotime("August 1, 2013 9:00 AM");
$remaining = $date - time();
$days_remaining = floor(($remaining % 2678400) / 86400);
?>
<div class="center">
    <img src="../images/funnybus.png" width ="500px" margin-botton="-50px"/>
    <h1><?php echo_trad("The adventure begins in");
            echo " ".$days_remaining." "; echo_trad("day");?>s.<br/>Get ready!
    </h1>
</div>