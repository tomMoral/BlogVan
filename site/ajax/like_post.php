<?php
include_once("../headerPHP.php");
$user=user::getSessionUser();
if($user!=null && isset($_POST['post_id'])){
    $post = Posts::get_by_id(htmlspecialchars($_POST['post_id']));
    if($post!=null && ($post->permission==0 || $user->type>0)){
        $post->add_like($user->id);
    }
}
?>
