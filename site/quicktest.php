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
                    var h = window.innerHeight;
                    var w = window.innerWidth;
        var Tmax = 5000;
        var img_w = 326;
        var img_h = 250;
        var t0 = new Date().getTime();
        var numCloud = 0;
        var myInterval;
        function create() {
            var t = new Date().getTime() - t0;
            $("div").css({"opacity": "" + ((Tmax - t) * (Tmax - t) / Tmax / Tmax)});
            if (t > 2 * Tmax) {

                for (var i = 0; i < numClouds; i++) {
                    alert("test");

                    $("#cloud" + i).css({"width": "0px"});
                    $("#cloud" + i).css({"height": "0px"});
                    var image_x = document.getElementById('cloud' + i);
                    image_x.parentNode.removeChild(image_x);
                    
                    window.innerHeight=h;
                    window.innerWidth=w;
                }
                clearInterval(myInterval);
                for (var i = 0; i < numClouds; i++) {
                    alert("test");

                    $("#cloud" + i).css({"width": "0px"});
                    $("#cloud" + i).css({"height": "0px"});
                    var image_x = document.getElementById('cloud' + i);
                    image_x.parentNode.removeChild(image_x);
                }
            }
            if (t < Tmax) {
                var chance = Math.random();
                if (chance > 1 - t / Tmax) {
                    var h = window.innerHeight;
                    var w = window.innerWidth;
                    var theta = 2 * Math.PI * Math.random();
                    var r = Math.random() * t * w / Tmax;
                    var x = r * Math.cos(theta) + h / 2 - img_h / 2;
                    var y = r * Math.sin(theta) + w / 2 - img_w / 2;
                    $("body").append('<img id ="cloud' + numCloud + '" src="../images/cloud.png" style="position:absolute; top:' + x + 'px;  left:' + y + 'px; width:326px; height:250px; z-index:' + (-1000 + numCloud) + ';"/>');

                    var myid = numCloud;
                    var w0 = x;
                    var h0 = y;
                    var r1 = img_w / img_h;
                    h = window.innerHeight;
                    w = window.innerWidth;
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
                    //$("#cloud" + myid).parent().remove("#div" + myid);
                }
            }
        }
        $(document).ready(function() {

            myInterval = setInterval(function() {
                $("div").css({"opacity": "1"});
                $("div").css({"z-index": "1"});
                var h = window.innerHeight;
                var w = window.innerWidth;
                $("div").attr("max-height", h + "px");
                $("div").attr("max-width", w + "px");
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