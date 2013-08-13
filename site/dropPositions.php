<?php
include("headerPHP.php");
$user = user::getSessionUser();
if ($user != null && $user->type == 2) {
    position::reset();
    header('Location: /uploadCSVFile.php');
    
}else{
      header('Location: /index.php');
}
?>