
<?php
//file not used ?
    include_once("headerPHP.php");
    $usr = user::getSessionUser();
    if (isset($_POST['vote'])) {
            Posts::vote(htmlspecialchars($_POST['id']), $usr, htmlspecialchars($_POST['vote']));
    header("Location: index.php?vote=true");
    }
    elseif (isset($_POST['body'])){
            $perm = (isset($_POST['permission']))?1:0;
            Posts::add_comment(htmlspecialchars($_POST['id']), $usr->name, htmlspecialchars($_POST['body']));
    }
    
    header("Location: index.php");
    exit;
?>