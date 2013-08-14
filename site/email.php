<?php
include_once("headerPHP.php"); //les post sont enregistré avec notre horloge, donc heure USA
htmlHeader("blog");
if (isset($_POST['object']) && isset($_POST['email'])) {
    
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From: baba-riders@california-here-we.com' . "\r\n".
            'Reply-To: baba-riders@california-here-we.com . "\r\n" ';

    $succeed = true;
    
    $db = database::connect();
    $query = $db->prepare("SELECT `email`, `name` FROM `user` WHERE `language` = 'EN' AND `type` = 2");
    $query->execute();
    while($user = $query->fetch(PDO::FETCH_ASSOC)){
        $succeed &= mail($user['email'], $_POST['object'], "Hey ".$user['name']."!<br/>\n\r".$_POST['email'], $headers);
        echo "send to ".$user['name']."<br/>";
    }
    
    $db = database::connect();
    $query = $db->prepare("SELECT `email`, `name` FROM `user` WHERE `language` = 'FR' AND `type` = 2");
    $query->execute();
    while($user = $query->fetch(PDO::FETCH_ASSOC)){
        $succeed &= mail($user['email'], $_POST['objectFR'], "Hey ".$user['name']."!<br/>\n\r".$_POST['emailFR'], $headers);
        echo "send to ".$user['name']."<br/>";
    }
    
    if ($succeed) {
        echo"email send!";
    } else {
        echo "error while sending email";
    }
}
?>
<form action="email.php" method="post" enctype="multipart/form-data" id="np">
    <input type="text" name="object" placeholder="Object" required="required"></br></br>
    <textarea type="text" id="postarea" name="email" placeholder='email' required="required"></textarea></br><br>
    <input type="text" name="objectFR" placeholder="ObjectFR" required="required"></br></br>
    <textarea type="text" id="postareaFR" name="emailFR" placeholder='emailFR' required="required"></textarea></br><br>
    <input type="submit"></br></br>
</form>




<?php
include_once("footer.php");
?>
