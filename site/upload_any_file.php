<?php
include("headerPHP.php");
$user = user::getSessionUser();
if ($user != null && $user->type == 2) {
    if (isset($_FILES['file'])) {
        move_uploaded_file($_FILES['file']['tmp_name'], "upload/" .$_FILES['file']['name']);
    }
    htmlHeader("photo");
    $A = scandir("upload");
    for ($i = 0; $i < count($A); $i++) {
        if (substr($A[$i], 0, 1) != ".") {
            echo "upload/" . $A[$i] . "<br/>";
        }
    }
    ?>
    <form action="upload_any_file.php" method="post" enctype="multipart/form-data" id="np">
        <input type="file"  name="file">
        <input type="submit"/>
    </form>
    <?php
} else {
    header('Location: index.php');
    die;
}
?>
