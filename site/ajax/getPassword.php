<?php

require '../class/database.php';
require '../class/user.php';
$nom = (isset($_POST["name"])) ? htmlspecialchars($_POST["name"]) : NULL;

if ($nom) {
    $user1 = user::getByName($nom);
    $user2 = user::getByEmail($nom);
    if ($user1 != null) {
        echo $user1->password;
    } else if ($user2 != null) {
        echo $user2->password;
    }
}
?>