<html>
    <head>
        <script type="text/javascript" src="jQuery.js"></script>
        <script type="text/javascript">


            var cursor;
            var selection;
            window.onload = init;

            function init() {
                cursor = $("#cursor");
            }

            function nl2br(txt) {
                return txt.replace(/\n/g, "<br />");
            }

            function writeit(from, e) {
                e = e || window.event;
                var w = $("#writer");
                var tw = from.value;
                w.text(tw);
            }

            function moveIt(count, e) {
                e = e || window.event;
                var keycode = e.keyCode || e.which;
//				alert(count);
                /*   if (keycode == 37 && parseInt(cursor.style.left) >= (0 - ((count - 1) * 10))) {
                 cursor.style.left = parseInt(cursor.style.left) - 10 + "px";
                 } else if (keycode == 39 && (parseInt(cursor.style.left) + 10) <= 0) {
                 cursor.style.left = parseInt(cursor.style.left) + 10 + "px";
                 }*/

            }


            function get_selection(element) {
                selection = String(window.getSelection());
                $('#display').text(selection);
                display_selection();
            }

            function display_selection() {
                var str = $("#writer").html();
                var myarr = str.split("<span style=\"background-color:red\">");
                str = "";
                for (var i = 0; i < myarr.length; i++) {
                    str += myarr[i];
                }
                myarr = str.split("</span>");
                str = "";
                for (var i = 0; i < myarr.length; i++) {
                    str += myarr[i];
                }
                var index = str.indexOf(selection);
                var replace = str.substr(0, index);
                replace += "<span style=\"background-color:red\">" + selection + "</span>" + str.substr(index + selection.length, str.length);
                $("#writer").html(replace);
            }

            function unselect() {
                if (selection !== "") {
                    selection = "";
                    display_selection();
                }

            }

        </script>


    </head>
    <body>
        <div id="terminal">
            <textarea type="text" id="setter" onkeydown="writeit(this, event);
                moveIt(this.value.length, event);"
                      onkeyup="writeit(this, event);"
                      onkeypress="writeit(this, event);">

            </textarea>
            <div id="getter" style="width:200px">
                <span id="writer" 
                      onkeyup="
                get_selection();"
                      onmousedown="
                unselect();"
                      onmouseup="
                get_selection();">bonjour world</span>

                </span>
                <b class="cursor" id="cursor">
                    <img src="images/face.png" style="width:20px; height:20px; vertical-align: bottom;"/>
                </b>
            </div>
            <div id="display">

            </div>
        </div>
    </body>

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
</html>