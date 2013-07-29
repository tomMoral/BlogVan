<?php
include("../headerPHP.php");
$user = user::getSessionUser();
if (isset($_POST['already_load'])) {
    $j = 0;
    $already_load = htmlspecialchars($_POST['already_load']);
    $perm = $user == null ? 0 : $user->type;
    $db = database::connect();
    $query = $db->prepare("SELECT * FROM `photos` WHERE `permission` <= $perm ORDER BY `time` DESC;");
    $query->execute();
    while (($photo = $query->fetch(PDO::FETCH_ASSOC)) && $j < $already_load + 2) {
        $j++;
        if ($j > $already_load) {
            $name = $photo['name'];
            echo $j % 2 == 1 ? '<tr><td>' : '<td>';
            if ($j % 2 == 1) {
                echo "<div class='photo photo_left' style='top'><img class='all_photos' src='" . $name . "'/> ";
            } else {
                echo "<div class='photo photo_right'><img class='all_photos' src='" . $name . "'/> ";
            }
            if ($user && $user->type == 2) {
                echo "<div class='delete_photo' id='" . $photo['id'] . "'><a class='delete_photo_a' href='#'>(" . string_trad("delete") . ")</a></div>";
            }
            echo $j % 2 == 1 ? "</div></td>" : "</div></td></tr>";
        }
    }
    if ($user != null && $user->type == 2) {
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
    }if ($j == $already_load + 2) {
        ?>
        <script>
            $(document).ready(function() {
                $.post("ajax/load_photo.php", {already_load: "<?php echo $j; ?>"})
                        .done(function(data) {
                    $("table").append(data);
                });
            });
        </script>
        <?php
    }
}
?>
