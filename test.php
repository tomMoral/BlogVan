<html>
    <head>
        <script type="text/javascript">
            function $(elid) {
                return document.getElementById(elid);
            }

            var cursor;
            window.onload = init;

            function init() {
                cursor = $("cursor");
                cursor.style.left = "0px";
            }

            function nl2br(txt) {
                return txt.replace(/\n/g, "<br />");
            }

            function writeit(from, e) {
                e = e || window.event;
                var w = $("writer");
                var tw = from.value;
                w.innerHTML = nl2br(tw);
            }

            function moveIt(count, e) {
                e = e || window.event;
                var keycode = e.keyCode || e.which;
//				alert(count);
                if (keycode == 37 && parseInt(cursor.style.left) >= (0 - ((count - 1) * 10))) {
                    cursor.style.left = parseInt(cursor.style.left) - 10 + "px";
                } else if (keycode == 39 && (parseInt(cursor.style.left) + 10) <= 0) {
                    cursor.style.left = parseInt(cursor.style.left) + 10 + "px";
                }

            }

            function alert(txt) {
                console.log(txt);
            }

        </script>

        <style type="text/css">
            body {
                margin: 0px;
                padding: 0px;
                height: 99%;
            }

            textarea#setter  {
                /*left: -1000px;
                position: absolute;*/
            }

            .cursor {
            }

            #terminal {
                margin: 8px;
                cursor: text;
                height: 500px;
                overflow: auto;
            }

            #writer {
                font-family: Helvetica, cursor, courier;
                font-size: 16px;
            }
            #getter {
                border: 1px solid #765942;
                border-radius: 10px;
                margin: 5px;
                padding: 10px;
            }
        </style>
    </head>
    <body>
        <div id="terminal" onclick="$('setter').focus();">
            <textarea type="text" id="setter" onkeydown="writeit(this, event);
                        moveIt(this.value.length, event)" onkeyup="writeit(this, event)" onkeypress="writeit(this, event);"></textarea>
            <div id="getter" style="width:200px">
                <span id="writer"></span><b class="cursor" id="cursor">  <img src="images/face.png" style="width:20px; height:20px; vertical-align: bottom;"/></b>
            </div>
        </div>
    </body>
</html>