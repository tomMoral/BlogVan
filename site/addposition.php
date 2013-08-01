<?php
//load the first 6 pictures and the other assyncronously
include_once("headerPHP.php");
$user = user::getSessionUser();

if ($user != null && $user->type == 2) {
    htmlHeader("blog");
    if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
        position::create($_POST['latitude'], $_POST['longitude'], date("Y-m-d H:i:s"));
    }
    ?>
    <form action="addposition.php" method="post" >
        <input type="text" name="latitude" placeholder="latitude" required="required"></br>
        <input type="text" name="longitude" placeholder="longitude" required="required"></br>
        <input type="submit" ></br>
    </form>


    <?php
    include_once("footer.php");
} else {
    header('Location: /index.php');
    die;
}
?>
