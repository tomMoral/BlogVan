<?php
//load the first 6 pictures and the other assyncronously
include_once("headerPHP.php");
htmlHeader("van");
$user = user::getSessionUser();
?><div style='text-align:center'>
<div class='center'><img src='images/bobby.JPG' width='530px'></div><br/>
ça c'est Van'oo le van<br/><br/><br/>
<div class='center'><img src='images/deal.JPG' width='530px'></div><br/>
ça c'est un monsieur qui a fait un bon deal<br/><br/><br/>
<div class='center'><img src='images/dodge.jpg' width='530px'></div><br/>
et voici à quoi vous avez échappé !
</div>
<?php

include_once("footer.php");
?>
