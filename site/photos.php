<?php
//load the first 6 pictures and the other assyncronously
include_once("headerPHP.php");
htmlHeader("photo");
if ($user != null && $user->type == 2 && isset($_GET['delete_photo'])) {
    $delete = htmlspecialchars($_GET['delete_photo']);
    photo::remove($delete);
}
?>
<div class="relative">
    <table>

        <?php
        $j = 0;
        $perm = $user == null ? 0 : $user->type;
        $db = database::connect();
        $query = $db->prepare("SELECT * FROM `photos` WHERE `permission` <= $perm ORDER BY `time` DESC;");
        $query->execute();
        while (($photo = $query->fetch(PDO::FETCH_ASSOC)) && $j < 6) {
            $name = $photo['medium'];
            $j++;
            echo $j % 2 == 1 ? '<tr><td>' : '<td>';
            if ($j % 2 == 1) {
                echo "<div class='photo photo_left' style='top'><img class='all_photos'  id='photo_" . $photo['id'] . "' src='" . $name . "' onload='resize(\"photo_" . $photo['id'] . "\");'/> ";
            } else {
                echo "<div class='photo photo_right'><img class='all_photos'  id='photo_" . $photo['id'] . "'  src='" . $name . "' onload='resize(\"photo_" . $photo['id'] . "\");'/> ";
            }
            if ($user && $user->type == 2) {
                echo "<div class='delete_photo' id='" . $photo['id'] . "'><a class='delete_photo_a' href='#'>(" . string_trad("delete") . ")</a></div>";
            }

            echo $j % 2 == 1 ? "</div></td>" : "</div></td></tr>";
        }


        if ($j == 6) {
            ?>
            <script>
                var maxWidth = 530;
                var maxHeight = 334;
                function resize(id_photo) {
                    var a = document.getElementById(id_photo);
                    if (a) {
                        if (a.width > maxWidth) {
                            a.width = maxWidth;
                            a.style.height = 'auto';
                        }
                        if (a.height > maxHeight) {
                            a.height = maxHeight;
                            a.style.width = 'auto';
                        }
                    }
                }
                $(document).ready(function() {
                    $.post("ajax/load_photo.php", {already_load: "<?php echo $j; ?>"})
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
                var id_photo = $(this).parent().attr("id");
                window.location = "photos.php?delete_photo=" + id_photo;
            });
        });
    </script>
    <?php
}
include_once("footer.php");
?>
