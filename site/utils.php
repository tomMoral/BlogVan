<?php

function dateToDuree($date) {
    //renvoie un truc du genre 'il y a 5 jours' à partir d'une datte au format 2013-03-01 00:11:56
    $a = strptime($date, "%Y-%m-%d %H:%M:%S");

    $timestamp = mktime($a['tm_hour'], $a['tm_min'], $a['tm_sec'], $a['tm_mon'] + 1, $a['tm_mday'], $a['tm_year'] + 1900);
    $diff = time() - $timestamp;
    
    if ($diff < 60) {
        return "less than one minute";
    } elseif ($diff < 3600) {
        return (($diff / 60) % 60) == 1 ? (($diff / 60) % 60) . " minute" : (($diff / 60) % 60) . " minutes";
    } elseif ($diff < 86400) {
        return (($diff / 3600) % 24) == 1 ? (($diff / 3600) % 24) . " hour" : (($diff / 3600) % 24) . " hours";
    } elseif ($diff < 604800) {
        return (($diff / 86400) % 7) == 1 ? (($diff / 86400) % 7) . " day" : (($diff / 86400) % 7) . " days";
    } elseif ($diff < 2678400) {
        return (($diff / 604800) % 5) == 1 ? (($diff / 604800) % 5) . " week" : (($diff / 604800) % 5) . " weeks";
    } elseif ($diff < 32140800) {
        return (($diff / 2678400) % 12) == 1 ? (($diff / 2678400) % 12) . " month" : (($diff / 2678400) % 12) . " months";
    } else {
        return "a long time";
    }
}

function htmlHeader($tohide) {
    //deso c'est un peu sale...
    echo '
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style/style.css" />
        <title>Maxi blog du Van VW :)</title>
        <link rel="icon" 
              type="image/png" 
              href="../images/logo.png">
        <script type="text/javascript" src="script/jQuery.js"></script>
        <script type="text/javascript" src="script/downloadScripts.js"></script>
        <script type="text/javascript" src="script/script.js"></script>
    </head>

    <body>
        <div id="bloc_page"><header> 
<div id="banniere_image">
    <div id="banniere_description">
        <nav>';
    if ($tohide != "blog") {
        echo '<a href="index.php"><div><img src="../images/face.png" alt="Logo VW" id="logo" />Blog</div></a>';
    }
    if ($tohide != "photo") {
        echo '               <a href="#"><div><img src="../images/face.png" alt="Logo VW" id="logo" />Photos</div></a>';
    }
    if ($tohide != "travel") {
        echo '       
                    <a href="travel.php"><div><img src="../images/face_blue.png" alt="Logo VW" id="logo" />Travel</div></a>';
    }
    if ($tohide != "team") {
        echo '       
                    <a href="#"><div><img src="../images/face_red.png" alt="Logo VW" id="logo" />Team</div></a>';
    }
    if ($tohide != "van") {
        echo '       
                    <div><a href="#"><img src="../images/face_green.png" alt="Logo VW" id="logo" />Van</div></a>';
    }
    if ($tohide != "connexion") {
        if (!isset($_SESSION['user'])) {
            echo '<a href="connexion.php"><div><img src="../images/face_yellow.png" alt="Logo VW" id="logo" />Get in</div></a>';
        } else {
            echo '<a href="deconnexion.php"><div><img src="../images/face_yellow.png" alt="Logo VW" id="logo" />Get out</div></a>';
        }
    }
    echo '
                </nav>
        
    </div>
</div>
                
            </header>';
}
?>
