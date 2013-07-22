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
    <h2>Au programme:</h2>
<ul>
    <li>
        2500 miles d'aventure de sueur et de rire 
    </li>
    <li>
        beaucoup de bières contre la chaleur de la Death Valley
    </li>
    <li>
        mariage à Vegas
    </li>
    <li>boules Quies quand Thomas chantera avec la radio
    </li>
    <li>
        divorce à Vegas
    </li>
    <li>
        une glacière pour mettre Micheaux dedans quand elle aura trop chaud et qu'on aura bu toutes les bieres
    </li>
    <li>
        une tente pour éviter à Marine de prendre toute la place dans le van
    </li>
    <li>
        banqueroute à Vegas
    </li>
    <li>
        pas de grimace sur les photos de Greg
    </li>
    <li>
        culturation dans des musées ou sur la plage au soleil
    </li>
    <li>
        panne régulière
    </li>
    <li>
    réveil de Guigui au volant
</li>
</ul>
    <h2>plein d'autre surprises et surtout</h2>

    <h1>beaucoup de posts et de photos !</h1>
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
