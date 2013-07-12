<?php

require_once("initSession.php");
require_once("class/database.php");
require_once("class/user.php");
$user = user::getSessionUser();
if ($user == null) {
    header('HTTP/1.0 302 Found');
    header('Location: http://california-here-we.com/index.php?deconnexion=true');
} else {
    $user->logOut();
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
