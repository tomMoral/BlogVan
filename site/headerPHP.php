<?php
include_once("headerPHPforConnexion.php");

$user = user::getSessionUser();
if($user==null){
    header("Location: connexion.php");
}
date_default_timezone_set('America/Los_Angeles');
?>