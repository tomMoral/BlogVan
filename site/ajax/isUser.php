<?php
require '../class/database.php';
require '../class/user.php';
$nom = (isset($_POST["name"])) ? $_POST["name"] : NULL;

if ($nom) {
    $user1 = user::getByName($nom);
    $user2 = user::getByEmail($nom);
    $pos = strrpos($nom, '@');
    $type = ($pos ===false) ? 'user name' : 'email';
    if ($user1 == null && $user2 == null) {
        echo $type;
    }
    else{
        echo "good";
    }
}
?>