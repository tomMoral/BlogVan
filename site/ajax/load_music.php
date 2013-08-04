<?php
include_once("../headerPHPforConnexion.php");

$A = scandir("../sounds/music");
for ($i = 0; $i < count($A); $i++) {
    if (substr($A[$i], 0, 1) != "." && explode('.', $A[$i])[1] == "mp3") {
        $A[$i] = "sounds/music/" . explode('.', $A[$i])[0];
    } else {
        $A[$i] = "";
    }
}
shuffle($A);
include("../moving_guy.php");
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
            if (audio.canPlayType('audio/mpeg;')) {
                audio.src = songs[song_num % nb_songs] + ".mp3";
            } else {
                audio.src = songs[song_num % nb_songs] + '.ogg';
            }
            song_num++;
            audio.load();
        }
        function myPlay() {
            audio.play();draw_play();
            audio.addEventListener('ended', function() {
                load();
                myPlay();
            });
        }


        audio.src = <?php
if (isset($_SESSION['currentTime']) && isset($_SESSION['playing']) && isset($_SESSION['song_num'])) {
    $temp = explode("/", $_SESSION['playing']);
    $l = count($temp);
    echo "\"sounds/music/" . $temp[$l - 1] . "\";\n";
    echo "song_num=" . $_SESSION['song_num'] . ";\n";
} else {
    ?>
            songs[song_num % nb_songs];
            song_num++;
            if (audio.canPlayType('audio/mpeg;')) {
                audio.src += ".mp3";
            } else {
                audio.src += '.ogg';
            }<?php
}
?>

        var startTime = <?php echo isset($_SESSION['currentTime']) ? $_SESSION['currentTime'] : "0"; ?>;
        var playing = <?php echo isset($_SESSION['is_playing']) && $_SESSION['is_playing'] == 1 ? "true" : "false"; ?>;
        audio.load();
        var is_playing = 0;
        var startTimeSet = false;
        function setStartTime() {
            if (!startTimeSet) {
                audio.currentTime = startTime;
                startTimeSet = true;
                if (playing) {
                    audio.play();
                    draw_play();
                } else {
                    audio.pause();
                    draw_stop();
                }
            }
        }
        ;

<?php
if (isset($_SESSION['currentTime']) && isset($_SESSION['playing']) && isset($_SESSION['song_num']) && isset($_SESSION['is_playing'])) {
    echo "is_playing = " . $_SESSION['is_playing'] . ";";
    if ($_SESSION['is_playing'] == 1) {
        echo ' $("#play_pause").attr("src", "images/pause.png");';
        echo "audio.addEventListener('canplaythrough', setStartTime, false);";
    }
    echo "audio.play();  draw_play();";
    if ($_SESSION['is_playing'] == 0) {
        echo "audio.addEventListener('canplaythrough', setStartTime, false);";
        echo ' $("#play_pause").attr("src", "images/play.png"); draw_stop();';
    }
    // echo "audio.currentTime = ".$_SESSION['currentTime'].";";
    $_SESSION['currentTime'] = null;
    $_SESSION['playing'] = null;
    $_SESSION['song_num'] = null;
} else {
    echo "audio.pause(); draw_stop();";
}
?>
        audio.addEventListener('ended', function() {
            load();
            myPlay();
        });



        $("#music").hide();
        $("#play_pause").click(function() {
            is_playing = is_playing === 0 ? 1 : 0;
            if (is_playing === 1) {
                audio.play();
                draw_play();
                $("#play_pause").attr("src", "images/pause.png");
            }
            else {
                audio.pause();
                draw_stop();
                $("#play_pause").attr("src", "images/play.png");
            }
            send_music(audio.currentTime, audio.src, songs, song_num, is_playing);
        });

        $(document).click(function(event) {
            send_music(audio.currentTime, audio.src, songs, song_num, is_playing);
        });

    });

</script>

