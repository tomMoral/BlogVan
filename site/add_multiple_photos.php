<?php
include("headerPHP.php");
include_once("class/resize.php");

$test = ini_get_all();
print_r($test['max_file_uploads']);
ini_set('max_file_uploads', 10);
$test = ini_get_all();
print_r($test['max_file_uploads']);
$user = user::getSessionUser();



if ($user != null && $user->type == 2) {
    if (isset($_FILES['pic'])) {
        $perm = isset($_POST['permission']) && $_POST['permission'] == "on" ? 1 : 0;
        print_r($_POST);
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
