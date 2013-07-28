<?php
include("headerPHP.php");
htmlHeader("travel");

$date = strtotime("August 1, 2013 9:00 AM");
$remaining = $date - time();
$days_remaining = floor(($remaining % 2678400) / 86400);
?>
<div class="center">
    <h1><?php echo_trad("The adventure begins in");
            echo " ".$days_remaining." "; echo_trad("day");?>s.<br/>Get ready!
    </h1>
    <img src ="images/planning.png" width =' 900px'>
    <h2><?php echo_trad("On the agenda"); ?>:</h2>
<ul>
    <li><?php echo_trad("2500 miles of aventure, sweat and laugther"); ?>
    </li>
    <li><?php echo_trad("lot of beers to fight Death Valley heat"); ?>
    </li>
    <li>
        <?php echo_trad("wedding at Vegas"); ?>
    </li>
    <li>
        <?php echo_trad("earplugs to let Thomas sing with the radio"); ?>
    </li>
    <li>
        <?php echo_trad("divorce at Vegas"); ?>
    </li>
    <li>
        <?php echo_trad("a cooler to put Micheaux in when she is too hot and the beers are gone"); ?>
    </li>
    <li>
        <?php echo_trad("a tent to prevent Marine from taking all the space in the van"); ?>
    </li>
    <li>
        <?php echo_trad("bankruptcy at Vegas"); ?>
    </li>
    <li>
        <?php echo_trad("no grimace on Greg's photos"); ?>
    </li>
    <li>
        <?php echo_trad("culturation in museums or lying on the beach"); ?>
    </li>
    <li>
        <?php echo_trad("car breakdown"); ?>
    </li>
    <li>
        <?php echo_trad("waking up Guigui while driving"); ?>
</li>
</ul>
    <h2>
        <?php echo_trad("a lot of other surprises and above all"); ?></h2>

    <h1><?php echo_trad("a lot of posts and photos !"); ?></h1>
</div>
<script>
    $(document).ready(function(){
        var n = $("ul").children().length;
        for(var i=0; i<n; i++){
            $("ul li:nth-child("+i+")").css("background", "url(images/face_"+Math.floor(7*Math.random())+"_small.png) no-repeat top left");
        }
    });
    </script>
        <?php

include_once("footer.php");
?>
