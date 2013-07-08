<?php

include_once("headerPHP.php");
htmlHeader("photo");
$user = user::getSessionUser();
if($user!=null && $user->type==2 && isset($_GET['delete_photo'])){
    unlink($_GET['delete_photo']);
}
echo '<div class="relative">';
if ($user == null || $user->type == 0) {
    $A = scandir("pics_up/A/");
    for ($i = 0; $i < count($A); $i++) {
        if (substr($A[$i], 0, 1) != ".") {
            $A[$i] = "pics_up/A/" . $A[$i];
        }
    }
    array_multisort($A, SORT_DESC);
    foreach ($A as $v) {
        if (substr($v, 0, 1) != ".") {
            echo "<div class='photo'><img class='all_photos' src='" . $v . "'/><div> ";
        }
    }
} else {
    $A = scandir("pics_up/A/");
    for ($i = 0; $i < count($A); $i++) {
        if (substr($A[$i], 0, 1) != ".") {
            $A[$i] = "pics_up/A/" . $A[$i];
        }
    }
    $B = scandir("pics_up/B/");
    for ($i = 0; $i < count($B); $i++) {
        if (substr($B[$i], 0, 1) != ".") {
            $B[$i] = "pics_up/B/" . $B[$i];
        }
    }

    $C = array_merge($A, $B);
    array_multisort($C, SORT_DESC);
    $i = 0;
    echo '<table>';
    foreach ($C as $v) {
        if (substr($v, 0, 1) != ".") {
            $i++;
            echo $i%2==1 ? '<tr><td>': '<td>';
            if ($user->type == 2) {
                if ($i % 2 == 1) {
                    echo "<div class='photo photo_left' style='top'><img class='all_photos' src='" . $v . "'/><div class='delete_photo'><a class='delete_photo_a' href='#'>(" . string_trad("delete") . ")</a></div></div> ";
                } else {
                    echo "<div class='photo photo_right'><img class='all_photos' src='" . $v . "'/><div class='delete_photo'><a class='delete_photo_a' href='#'>(" . string_trad("delete") . ")</a></div></div> ";
                }
            } else {
                echo "<div class='photo'><img class='all_photos' src='" . $v . "'/><div> ";
            }
            echo $i%2==1 ? "</td>" : "</td></tr>";
        }
    }echo '</table>';
}
echo '</div>';
if($user!=null && $user->type==2){
    ?>
<script>
    $(document).ready(function(){
       $(".delete_photo_a").click(function(){
          var id_photo =  $(this).parent().parent().children("img").attr("src");
           window.location = "photos.php?delete_photo="+id_photo;
       });
    });
    </script>

<?php
}
include_once("footer.php");
?>
