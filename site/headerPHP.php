<?php
include_once("initSession.php");

include_once("class/database.php");
include_once('class/post.php');
include_once('class/user.php');
include_once("class/database.php");
include_once("class/photos.php");
include_once('class/comments.php');
include_once('utils.php');
$user = new user();
$user->create("t", "mdp");
?>