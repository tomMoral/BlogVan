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
        echo '                    <div><a href="index.php"><img src="../images/face.png" alt="Logo VW" id="logo" />Blog</a></div>';
    }
    if ($tohide != "photo") {
        echo '               <div><a href="#"><img src="../images/face.png" alt="Logo VW" id="logo" />Photos</a></div>';
    }
    if ($tohide != "travel") {
        echo '       
                    <div><a href="#"><img src="../images/face_blue.png" alt="Logo VW" id="logo" />Travel</a></div>';
    }
    if ($tohide != "team") {
        echo '       
                    <div><a href="#"><img src="../images/face_red.png" alt="Logo VW" id="logo" />Team</a></div>';
    }
    if ($tohide != "van") {
        echo '       
                    <div><a href="#"><img src="../images/face_green.png" alt="Logo VW" id="logo" />Van</a></div>';
    }
    if ($tohide != "connexion") {
        if (!isset($_SESSION['user'])) {
            echo '<div><a href="connexion.php"><img src="../images/face_yellow.png" alt="Logo VW" id="logo" />Get in</a></div>';
        } else {
            echo '<div><a href="deconnexion.php"><img src="../images/face_yellow.png" alt="Logo VW" id="logo" />Get out</a></div>';
        }
    }
    echo '
                </nav>
        
    </div>
</div>
                
            </header>';
}
?>
