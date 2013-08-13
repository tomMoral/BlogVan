<?php
include_once("headerPHP.php"); //les post sont enregistré avec notre horloge, donc heure USA
htmlHeader("blog");
if (isset($_POST['destinataire']) && isset($_POST['object']) && isset($_POST['email'])) {
    
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    $headers .= 'From: baba-riders@california-here-we.com' . "\r\n".
    'Reply-To: baba-riders@california-here-we.com . "\r\n" ';
    $succeed = mail($_POST['destinataire'], $_POST['object'], $_POST['email'], $headers);
    if ($succeed) {
        echo"eamil send!";
    } else {
        echo "error while sending email";
    }
}
?>
<form action="email.php" method="post" enctype="multipart/form-data" id="np">
    <input type="text" name="destinataire"  placeholder="Destinataire" required="required"></br></br>
    <input type="text" name="object" placeholder="Object" required="required"></br></br>
    <textarea type="text" id="postarea" name="email" placeholder='email' required="required"></textarea></br><br>
    <input type="submit"></br></br>
</form>




<?php
include_once("footer.php");
?>
