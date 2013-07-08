<?php
//load the first 6 pictures and the other assyncronously
include_once("headerPHP.php");
htmlHeader("photo");
$user = user::getSessionUser();
if ($user != null && $user->type == 2 && isset($_GET['delete_photo'])) {
    $delete = htmlspecialchars($_GET['delete_photo']);
    if (file_exists($delete)) {
        unlink($delete);
    }
}
?>
<div class="relative">
    <table>

        <?php
        $j = 0;
        $last_id = -1;
        if ($user == null || $user->type == 0) {
            $A = scandir("pics_up/A/");
            for ($i = 0; $i < count($A); $i++) {
                if (substr($A[$i], 0, 1) != ".") {
                    $A[$i] = "pics_up/A/" . $A[$i];
                }
            }
            array_multisort($A, SORT_DESC);
            foreach ($A as $v) {
                if (substr($v, 0, 1) != "." && $j < 6 && ($last_id == -1 || strcmp($last_id, $v) > 0)) {
                    echo $j;
                    $j++;
                    echo $j % 2 == 1 ? '<tr><td>' : '<td>';
                    if ($j % 2 == 1) {
                        echo "<div class='photo photo_left' style='top'><img class='all_photos' src='" . $v . "'/></div> ";
                    } else {
                        echo "<div class='photo photo_right'><img class='all_photos' src='" . $v . "'/></div> ";
                    }
                    echo $j % 2 == 1 ? "</td>" : "</td></tr>";
                    $last_id = $v;
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
            foreach ($C as $v) {
                if (substr($v, 0, 1) != "." && $j < 6 && ($last_id == -1 || strcmp($last_id, $v) > 0)) {
                    $j++;
                    echo $j % 2 == 1 ? '<tr><td>' : '<td>';
                    if ($user->type == 2) {
                        if ($j % 2 == 1) {
                            echo "<div class='photo photo_left' style='top'><img class='all_photos' src='" . $v . "'/><div class='delete_photo'><a class='delete_photo_a' href='#'>(" . string_trad("delete") . ")</a></div></div> ";
                        } else {
                            echo "<div class='photo photo_right'><img class='all_photos' src='" . $v . "'/><div class='delete_photo'><a class='delete_photo_a' href='#'>(" . string_trad("delete") . ")</a></div></div> ";
                        }
                    } else {
                        if ($j % 2 == 1) {
                            echo "<div class='photo photo_left' style='top'><img class='all_photos' src='" . $v . "'/></div> ";
                        } else {
                            echo "<div class='photo photo_right'><img class='all_photos' src='" . $v . "'/></div> ";
                        }
                    }
                    echo $j % 2 == 1 ? "</td>" : "</td></tr>";
                    $last_id = $v;
                }
            }
        }if ($j == 6) {
            ?>
            <script>
                $(document).ready(function() {
                    $.post("ajax/load_photo.php", {last_id: "<?php echo $last_id; ?>"})
                            .done(function(data) {
                        $("table").append(data);
                    });
                });
            </script>
<?php }
?>

    </table>
</div>

<?php if ($user != null && $user->type == 2) {
    ?>
    <script>
        $(document).ready(function() {
            $(".delete_photo_a").click(function() {
                var id_photo = $(this).parent().parent().children("img").attr("src");
                window.location = "photos.php?delete_photo=" + id_photo;
            });
        });
    </script>
    <?php
}
include_once("footer.php");
?>
