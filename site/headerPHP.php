<?php
require_once("initSession.php");

include('class/post.php');
include('class/user.php');
include('utils.php');
$user = new user();
$user->create("t", "mdp");
?>