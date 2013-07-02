<?php
include("../headerPHP.php");
$user = user::getSessionUser();



$text = isset($_POST['text']) && htmlspecialchars($_POST['text']) != "" ? str_replace('linebreak', '<br/>', str_replace('linebreaklinebreak', '</p><p>', htmlspecialchars($_POST['text']))) : NULL;
if ($text != "") {
    $id_post = isset($_POST['id_post']) && htmlspecialchars($_POST['id_post']) != "" ? htmlspecialchars($_POST['id_post']) : NULL;
    $id_com = Posts::add_comment($id_post, $user->name, $text);
    $com = Comments::get_com_by_id($id_com);
    ?>
    <div class="comment">
        <h1><?php echo $com->user . '  </h1><legend>' . dateToDuree($com->time) . ' </legend>'; ?>
            <p> <?php echo $com->body; ?> </p>
    </div><?php
}?>