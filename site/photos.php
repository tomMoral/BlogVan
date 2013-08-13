<?php
//load the first 6 pictures and the other assyncronously
include_once("headerPHP.php");
htmlHeader("photo");
if ($user != null && $user->type == 2 && isset($_GET['delete_photo'])) {
    $delete = htmlspecialchars($_GET['delete_photo']);
    photo::remove($delete);
}
$photos = array();
$id_to_num = array();
$db = database::connect();
$perm = $user->type;
$query = $db->prepare("SELECT `medium`, `original`, `id` FROM `photos` 
                       WHERE `permission`<=$perm ORDER BY `time` DESC");
$query->execute();
$i = 0;
while ($photo = $query->fetch(PDO::FETCH_ASSOC)) {
    $temp = array();
    $temp['id'] = $photo['id'];
    $temp['original'] = $photo['original'];
    $temp['medium'] = $photo['medium'];
    $photos[$i] = $temp;
    $id_to_num[$photo['id']] = $i;
    $i++;
}
$query->closeCursor();
?>
<div class="relative">
    <div id="diaporama" ><h1>diaporama</h1></div>
    <script>
        $("#diaporama").click(function() {
            displayFullScreen(photos[idDiapo]['original']);
        });
        var photos = <?php echo json_encode($photos); ?>;
        var idToNum =<?php echo json_encode($id_to_num); ?>;
        var idDiapo = 0,
                diapoLenght = photos.length,
                diaporamaRunning = false,
                firstDiapo = true, loaded = "";
        function diapoNext() {
            idDiapo = (idDiapo + 1 + diapoLenght) % diapoLenght;
            displayFullScreen(photos[idDiapo]['original']);
            // if (diaporamaRunning)
            //   setTimeout(diapoNext, 2000);
        }
        function diapoPrev() {
            idDiapo = (idDiapo - 1 + diapoLenght) % diapoLenght;
            displayFullScreen(photos[idDiapo]['original']);
            //if (diaporamaRunning)
            //  setTimeout(diapoNext, 2000);
        }
        function displayFullScreen(src) {
            if (firstDiapo) {
                firstDiapo = false;
                $("body").append("<img src='images/right_arrow.png' class='arrow' id='right_arrow'/>");
                $("body").append("<img src='images/left_arrow.png' class='arrow' id='left_arrow'/>");
                $("body").append("<img src='images/cross.png' class='arrow' id='cross'/>");
                $(document).bind('keydown', keyboardHandler);
                $('#cross').click(close);
                $('#left_arrow').click(diapoPrev);
                $('#right_arrow').click(diapoNext);
            }

            src = src.indexOf("Icon") !== -1 ? src.replace("Icon", "") : src;
            $("#full_screen_photo").remove();
            $("#full_screen_background").remove();
            $("body").append("<img src='" + src + "' id='full_screen_photo' onload='resizeDiapo();'/>");

            $("body").append("<img src='images/black.jpg' id='full_screen_background'/>");
            if (loaded !== "") {
                $("#for_load1").remove();
                $("#for_load2").remove();
            }
            var previous = (idDiapo - 1 + diapoLenght) % diapoLenght;
            $("body").append("<div id='for_load1'><img src='" + photos[previous]['original'] + "'/></div>");
            var next = (idDiapo + 1 + diapoLenght) % diapoLenght;
            $("body").append("<div id='for_load2'><img src='" + photos[next]['original'] + "'/></div>");
            loaded = "1";
        }

        function close() {
            $("#full_screen_photo").remove();
            $("#full_screen_background").remove();
            $("#right_arrow").remove();
            $("#left_arrow").remove();
            $("#cross").remove();
            $("#for_load1").remove();
            $("#for_load2").remove();
            load = "";
            $(document).unbind('keydown', keyboardHandler);
            firstDiapo = true;
        }



        function resizeDiapo() {
            var windowW = window.innerWidth;
            var windowH = window.innerHeight;
            var imgW = windowW - 150;
            var imgH = windowH * 0.9;
            var bigPic = $("#full_screen_photo");
            //set the image size
            var width = parseInt(bigPic.css("width").replace("px", ""));
            var height = parseInt(bigPic.css("height").replace("px", ""));
            if (width > imgW) {
                var ratio = width / height;
                bigPic.css("width", imgW + "px");
                bigPic.css("height", imgW / ratio + "px");
            }
            width = parseInt(bigPic.css("width").replace("px", ""));
            height = parseInt(bigPic.css("height").replace("px", ""));
            if (height > imgH) {
                var ratio = width / height;
                bigPic.css("height", imgH + "px");
                bigPic.css("width", imgH * ratio + "px");
            }
            height = parseInt(bigPic.css("height").replace("px", ""));
            width = parseInt(bigPic.css("width").replace("px", ""));
            //set the image position
            bigPic.css("left", ((windowW - width) / 2) + "px");
            bigPic.css("top", ((windowH - height) / 2) + "px");
            $("#right_arrow").css("right", ((windowW - width) / 2) - 50 + "px");
            $("#left_arrow").css("left", ((windowW - width) / 2) - 50 + "px");
            $("#right_arrow").css("top", (windowH / 2) - 20 + "px");
            $("#left_arrow").css("top", (windowH / 2) - 20 + "px");

        }
        function getOffset(el) {
            var _x = 0;
            var _y = 0;
            while (el && !isNaN(el.offsetLeft) && !isNaN(el.offsetTop)) {
                _x += el.offsetLeft - el.scrollLeft;
                _y += el.offsetTop - el.scrollTop;
                el = el.offsetParent;
            }
            return {top: _y, left: _x};
        }

        function keyboardHandler(event) {
            switch (event.which) {
                case 37:
                case 38:
                    diapoPrev();
                    break;
                case 39:
                case 40:
                    diapoNext();
                    break;
                case 27:
                    close();
            }

        }

    </script>
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
                var maxWidth = 445;
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
<script>
    $(document).ready(function() {
        $(".all_photos").click(function() {
            var id = parseInt(this.id.replace("photo_", ""));
            var num = idToNum[id];
            idDiapo = num;
            displayFullScreen(photos[num]['original']);
        });
    });
</script>

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
