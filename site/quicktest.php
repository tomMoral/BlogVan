
</html><!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8' />
    </head>


    <body >
        <audio id="audio" src="sounds/music/CalifornicationRedHot.ogg" type="audio/ogg" controls="controls"></audio>
        <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
        <script>
            var set=false;
            var audio = new Audio();
            audio.src = "sounds/music/CalifornicationRedHot.ogg";
            audio.load();
            audio.addEventListener('canplaythrough', setStartTime, false);
            function setStartTime(){
                if (!set){
                audio.currentTime = 30;
                audio.play();
                set=true;
                }
            };
            $(document).click(function(){
                audio.pause();
            });
        </script>
    </body>
</html>

