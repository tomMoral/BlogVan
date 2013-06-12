<?php
include("headerPHP.php");
$user = user::getSessionUser();
if ($user != null && $user->type == 2) {
    if (!isset($_POST['post'])) {
        htmlHeader("blog");
        ?>

        <div id="post">
            <form action="new_post.php" method="post">
                Titre: <input type="text" name='title'><br>
                Post: <textarea type="text" name="post" placeholder='New Post, insert photo at [p]'></textarea><br>
                Permission: <input type="checkbox" name="permission" value=1 checked="checked">All<br>
                <input type="submit">
            </form>
        </div>
        <?php
    } else {
        $perm = (isset($_POST['permission'])) ? 1 : 0;
        $count = preg_match_all('/\[([^:]:?)+\]/', $_POST['post']);
        if ($count == 0)
            $comments = '';
        else
            $comments = 'v';
        $return = Posts::add_post('GPS1', $_POST['title'], $_POST['post'], '', $comments, $perm);
        if ($return == '')
            header("Location: index.php");
        else
            echo $return;
        exit;
    }
}else {
    header("Location: index.php");
}?>