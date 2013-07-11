<?php
include_once("../headerPHP.php");
$A = scandir("../../sounds/music");
for ($i = 0; $i < count($A); $i++) {
    if (substr($A[$i], 0, 1) != ".") {
        $A[$i] = "../sounds/music/" . $A[$i];
    } else {
        $A[$i] = "";
    }
}
shuffle($A);
?>
<script>

    $(document).ready(function() {
        var songs = [<?php
if (!isset($_SESSION['songs'])) {
    $count = 0;
    for ($i = 0; $i < count($A); $i++) {
        echo $A[$i] == "" ? "" : "\"" . $A[$i] . "\",";
        $count+= $A[$i] == "" ? 0 : 1;
    }
} else {
    $count = count($_SESSION['songs']);
    foreach ($_SESSION['songs'] as $song) {
        echo "\"" . $song . "\",";
    }
    $_SESSION['songs'] = null;
}
?>];
        var nb_songs =<?php echo $count; ?>;
        var song_num = 0;
        var audio = new Audio();
        function load() {
            audio.src = songs[song_num % nb_songs];
            song_num++;
            audio.load();
        }
        function myPlay() {
            audio.play();
            audio.addEventListener('ended', function() {
                load();
                myPlay();
            });
        }
        audio.src = <?php
if (isset($_SESSION['currentTime']) && isset($_SESSION['playing']) && isset($_SESSION['song_num'])) {
    $temp = explode("/", $_SESSION['playing']);
    $l = count($temp);
    echo "\"../sounds/music/" . $temp[$l - 1] . "\";\n";
    echo "song_num=" . $_SESSION['song_num'] . ";\n";
} else {
    ?>
            songs[song_num % nb_songs];
            song_num++;<?php
}
?>
        audio.load();
        var playing = 0;

<?php
if (isset($_SESSION['currentTime']) && isset($_SESSION['playing']) && isset($_SESSION['song_num'])) {
    echo "playing = 1;";
    echo ' $("#manage_music img").attr("src", "../images/pause.png");';
    $_SESSION['currentTime'] = null;
    echo "audio.play();";
    // echo "audio.currentTime = ".$_SESSION['currentTime'].";";
    $_SESSION['playing'] = null;
    $_SESSION['song_num'] = null;
} else {
    echo "audio.play();";
    echo "audio.pause();";
}
?>
        audio.addEventListener('ended', function() {
            load();
            myPlay();
        });




        $("#music").hide();
        $("#manage_music").click(function() {
            playing = playing === 0 ? 1 : 0;
            if (playing === 1) {
                audio.play();
                $("#manage_music img").attr("src", "../images/pause.png");
            }
            else {
                audio.pause();
                $("#manage_music img").attr("src", "../images/play.png");
            }
        });

        $("a").click(function() {
            if (playing === 1) {
                $.post("ajax/continue_music.php", {currentTime: audio.currentTime, playing: audio.src, songs: songs, song_num: song_num})
                        .done(function(data) {
                });
            }
        });
    });
</script>