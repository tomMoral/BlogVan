<?php
function dateToDuree($date) {
    //renvoie un truc du genre 'il y a 5 jours' à partir d'une datte au format 2013-03-01 00:11:56
    $a = strptime($date, "%Y-%m-%d %H:%M:%S");
    
    $timestamp = mktime($a['tm_hour'], $a['tm_min'], $a['tm_sec'], $a['tm_mon'] + 1, $a['tm_mday'], $a['tm_year'] + 1900);
    $diff = time() - $timestamp;
    if ($diff < 60) {
        return "less than one minute";
    } elseif ($diff < 3600) {
        return (($diff / 60) % 60)==1 ? (($diff / 60) % 60) . " minute" : (($diff / 60) % 60)." minutes";
    } elseif ($diff < 86400) {
        return (($diff / 3600) % 24)==1 ? (($diff / 3600) % 24) . " hour" : (($diff / 3600) % 24)." hours";
    } elseif ($diff < 604800) {
        return (($diff / 86400) % 7)==1 ? (($diff / 86400) % 7) . " day" : (($diff / 86400) % 7)." days";
    } elseif ($diff < 2678400) {
        return (($diff / 604800) % 5)==1 ? (($diff / 604800) % 5) . " week" : (($diff / 604800) % 5)." weeks";
    } elseif ($diff < 32140800) {
        return (($diff / 2678400) % 12)==1 ? (($diff / 2678400) % 12) . " month" : (($diff / 2678400) % 12)." months";
    } else {
        return "a long time";
    }
}
?>
