<?php
$A = scandir("../sounds/music");
for ($i = 0; $i < count($A); $i++) {
    if (substr($A[$i], 0, 1) != ".") {
        $A[$i] = "sounds/music/" . $A[$i];
    } else {
        $A[$i] = "";
    }
}
shuffle($A);
?>
<script>

    $(document).ready(function() {
        var songs = [<?php
$count = 0;
for ($i = 0; $i < count($A); $i++) {
    echo $A[$i] == "" ? "" : "\"".$A[$i] . "\",";
    $count+= $A[$i] == "" ? 0 : 1;
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
        audio.src = songs[song_num % nb_songs];
        song_num++;
        audio.load();
        audio.play();
        audio.pause();
        audio.addEventListener('ended', function() {
            load();
            myPlay();
        });




        $("#music").hide();
        var playing = 0;
        $("#manage_music").click(function() {
            playing = playing === 0 ? 1 : 0;
            if (playing === 1) {
                audio.play();
                $("#manage_music img").attr("src", "images/pause.png");
            }
            else {
                audio.pause();
                $("#manage_music img").attr("src", "images/play.png");
            }
        });


    });
</script>