<?php

require_once("initSession.php");
require_once("class/database.php");
require_once("class/user.php");
$user = user::getSessionUser();
if ($user == null) {
    header('Location: /index.php?deconnexion=true');
} else {
    $user->logOut();
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
