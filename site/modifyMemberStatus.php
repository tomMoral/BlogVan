<?php
include("headerPHP.php");
$user = user::getSessionUser();
if ($user != null && $user->type == 2) {
    user::set_status($_POST['status'], $_POST['id']);
    header('Location: admin.php');
}
?>
