<?php
include("headerPHP.php");
include_once("class/resize.php");
$user = user::getSessionUser();



if ($user != null && $user->type == 2) {
    if (isset($_FILES['pic']) && isset($_POST['permission'])) {
        $perm = $_POST['permission'];
        $reverse = array();
        $pics = $_FILES['pic'];
        $n = count($pics['name']);
        for ($i = 0; $i < $n; $i++) {
            $reverse[$i]['name'] = $pics['name'][$i];
            $reverse[$i]['tmp_name'] = $pics['tmp_name'][$i];
        }

        foreach ($reverse as $pic) {
            echo($pic['name']);
            photo::add($pic['tmp_name'], $pic['name'], $perm);
            echo"<br/>";
        }
    }
    htmlHeader("photo");
    ?>
Pas plus de 20 d'un coup!
    <form action="add_multiple_photos.php" method="post" enctype="multipart/form-data" id="np">
        <input type="file" multiple  name="pic[]" id="pics">
        Permission: <input type="checkbox" name="permission" checked="checked">
        <input type="submit"/>
    </form>
    <?php
} else {
    header('Location: index.php');
    die;
}
?>
