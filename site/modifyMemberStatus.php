<?php

include("headerPHP.php");
user::set_status($_POST['status'], $_POST['id']);
header('Location: admin.php');
?>
