<?php
include_once("../headerPHP.php"); 
if(isset($_POST['songs'])){
    $_SESSION['songs']=$_POST['songs'];
}
if(isset($_POST['playing'])){
    $_SESSION['playing']=$_POST['playing'];
}
if(isset($_POST['currentTime'])){
    $_SESSION['currentTime']=$_POST['currentTime'];
}
if(isset($_POST['song_num'])){
    $_SESSION['song_num']=$_POST['song_num'];
}
if(isset($_POST['is_playing'])){
    $_SESSION['is_playing']=$_POST['is_playing'];
}
?>