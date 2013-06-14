<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style/style.css" />
        <title>Maxi blog du Van VW :)</title>
        <link rel="icon" 
              type="image/png" 
              href="../images/logo.png">
        <script type="text/javascript" src="script/jQuery.js"></script>
        <script type="text/javascript" src="script/downloadScripts.js"></script>
        <script type="text/javascript" src="script/script.js"></script>
    </head>
    <script>
        var Tmax = 5000;
        var img_w = 326;
        var img_h = 250;
        var t0 = new Date().getTime() / 1000;
        var numCloud = 0;
        var myInterval;
        function create() {
            var t = new Date().getTime() / 1000 - t0;
            if(t*1000>Tmax){
                clearInterval(myInterval);
            }
            var chance = Math.random();
            if (chance > 1 - t * 1000 / Tmax) {
                var h = window.innerHeight;
                var w = window.innerWidth;
                var theta = 2 * Math.PI * Math.random();
                var r = Math.random() * t * 1000 * w / Tmax;
                var x = r * Math.cos(theta) + h / 2 - img_h / 2;
                var y = r * Math.sin(theta) + w / 2 - img_w / 2;
                $("div").append('<img id ="cloud' + numCloud + '" src="../images/cloud.png" style="overflow:hidden; position:absolute; top:' + x + 'px;  left:' + y + 'px; width:326px; height:250px z-index:' + numCloud + ';"/>');

                var myid=numCloud;
                var w0 = x;
                var h0 = y;
                var r1 = img_w / img_h;
                var h = window.innerHeight;
                var w = window.innerWidth;
                var r2 = w / h;
                var obj = 300;
                numCloud += 1;
                $("#cloud" + myid).animate({
                    width: obj + "%",
                    height: "" + (obj / r1 * r2) + "%",
                    opacity: 0,
                    marginLeft: w0 - obj / 200 * w + "px",
                    marginTop: h0 - obj * r2 / r1 / 200 * h + "px",
                    fontSize: "3em",
                    borderWidth: "10px"
                }, Tmax);
                $("#cloud" + myid).parent().remove("#cloud" + myid);
            }
        }
        $(document).ready(function() {
        
           myInterval=  setInterval(function() {
               
                var h = window.innerHeight;
                var w = window.innerWidth;
                $("div").attr("max-height", h+"px");
                $("div").attr("max-width", w+"px");
                create();
            }, 100);
        });


    </script>
    <body>
        <div style="overflow:hidden;">
            <textarea></textarea>
        </div>
    </body>
</html>