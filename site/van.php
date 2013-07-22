<?php
//load the first 6 pictures and the other assyncronously
include_once("headerPHP.php");
htmlHeader("van");
$user = user::getSessionUser();
?>ça c'est Boby le van
<div class='center'><img src='images/bobby.JPG' width='530px'></div>
ça c'est un monsieur qui a fait un bon deal
<div class='center'><img src='images/deal.JPG' width='530px'></div>
et voici à quoi vous avez échappé !
<div class='center'><img src='images/dodge.jpg' width='530px'></div>

<?php

include_once("footer.php");
?>
