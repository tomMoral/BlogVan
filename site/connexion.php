<?php
include("headerPHP.php");
$user = user::getSessionUser();
if ($user) {
    header('Location: index.php');
}
$bad_password = false;
if (isset($_POST['name'])) {
    $name = htmlspecialchars($_POST['name']);
    if (isset($_SESSION['try_connexion'][$name])) {
        $_SESSION['try_connexion'][$name]++;
    } else {
        $_SESSION['try_connexion'] = null;
        $_SESSION['try_connexion'][$name] = 1;
    }
    $password = sha1(htmlspecialchars($_POST['password']));
    $language = isset($_POST['language']) ? htmlspecialchars($_POST['language']) : "EN";
    $pos = strrpos($name, '@');
    $type = ($pos === false) ? 'name' : 'email';

    if ($type == 'name') {
        $user = user::getByName($name);
        if ($user == null) {
            //then create user
            $email = htmlspecialchars($_POST['email']);
            user::create($name, $password, $email, $language);
            header('Location: index.php?firstconnexion=true');
        } else {
            //then identify user
            if ($user->loginByName($name, $password)) {
                header('Location: index.php?connexion=true');
            } else {
                $bad_password = true;
            }
        }
    } else {
        //similar to first case
        $user = user::getByEmail($name);
        if ($user == null) {
            //then create user
            $email = htmlspecialchars($_POST['email']);
            user::create($email, $password, $name, $language);
            header('Location: index.php?firstconnexion=true');
        } else {
            //then identify user
            if ($user->loginByEmail($name, $password)) {
                header('Location: index.php?connexion=true');
            } else {
                $bad_password = true;
            }
        }
    }
}
htmlHeader("connexion");
?>
<script type="text/javascript">
//this script is used for managing the connexion/inscription
    var thirdRow = false;
    var value = "";
    var changedEmail = false;
    var language = "EN";

    function check(type) {
        //tell if the name/email is already used
        $("#email").keyup(function() {
            var email = $("#email").val();
            if (email !== "") {
                $.post("ajax/isUser.php", {name: email})
                        .done(function(data) {
                    callBackCheck(data, type);
                });
            }
        });
    }
    ;

    function callBackCheck(sData, type) {
        //if the name/email is already used, we say so
        if (sData === "good") {
            $("#side").html('<font color="red"><?php echo_trad("Someone is already using this "); ?>' + type + ', <?php echo_trad("try an other one"); ?>!</font>');
            $("#go").hide();
            $("#space").show();

            changedEmail = true;
        }
        else if (sData !== "user name" && sData !== "email") {
            alert(sData);
        }
        else {
            if (changedEmail) {
                $("#side").html('<font color="green"><?php echo_trad("This"); ?> ' + type + ' <?php echo_trad("is available"); ?>!</font>');
            } else {
                $("#side").html('');
            }
        }
    }

    function add(label) {
        //if needed, add a row for inscription
        thirdRow = true;
        var str = '<tr class="new"> \n\
<td>\n\
<input type=text name="email" class="email" id="email" maxlength="255" placeholder="' + label + '" value="' + value + '"/>\n\
</td>\n\
</tr>\n\
<tr class="new">\n\
<td>\n\
<div class="left">This is America!<br/>\n\
<img src="images/burger1.png" width="150px"/><br/>\n\
(and I speak English)<br/>\n\
<input type="radio" name="language" checked="checked" value="EN"/>\n\
</div>\n\
<div class="right">Mon royaume pour un fromage!<br/>\n\
<img src="images/camembert.png" width="110px"/><br/>\n\
(et je parle français)<br/>\n\
<input type="radio" name="language"';
        if (language === "FR") {
            str += 'checked="checked"';
        }
        str += ' value="FR"/>\n\
</div>\n\
</td>';
        if (label === "Email") {
            $("#side").html('<font color="green"><?php echo_trad("You don\'t seem to be registered yet, please provide an email adress and chose your language"); ?></font>');
        } else {
            $("#side").html('<font color="green"><?php echo_trad("You don\'t seem to be registered yet, please provide a user name and chose your language"); ?></font>');
        }
        var newRow = $(str);
        $("#secondRow").after(newRow);
        $("#email").keyup(function() {
            if (label === "Email") {
                check('email');
            } else {
                check('user name');
            }
            var name = $("#name").val();
            var pass = $("#password").val();
            var a = $("#email").val();
            if (name !== "" && pass !== "" && (label !== "Email" || validateEmail(a))) {
                $("#go").show();
                $("#space").hide();
            }
            else {
                $("#go").hide();
                $("#space").show();
            }
        });
        interactLanguage();
    }

    function interactLanguage() {
        $(".left").click(function() {
            language = "EN";

            $(".left").parent().html('<div class="left">This is America!<br/>\n\
<img src="images/burger1.png" width="150px"/><br/>\n\
(and I speak English)<br/>\n\
<input type="radio" name="language" checked="checked" value="EN"/>\n\
</div>\n\
<div class="right">Mon royaume pour un fromage!<br/>\n\
<img src="images/camembert.png" width="110px"/><br/>\n\
(et je parle français)<br/>\n\
<input type="radio" name="language" value="FR"/>\n\
</div>');

            interactLanguage();
        });
        $(".right").click(function() {
            language = "FR";

            $(".left").parent().html('<div class="left">This is America!<br/>\n\
<img src="images/burger1.png" width="150px"/><br/>\n\
(and I speak English)<br/>\n\
<input type="radio" name="language" value="EN"/>\n\
</div>\n\
<div class="right">Mon royaume pour un fromage!<br/>\n\
<img src="images/camembert.png" width="110px"/><br/>\n\
(et je parle français)<br/>\n\
<input type="radio" name="language" value="FR" checked="checked"/>\n\
</div>');
            interactLanguage();
        });
    }

    function deleteRow() {
        //if we don't need the row anymore, we delete it
        language = $('input[name="language"]:checked').val();
        thirdRow = false;
        $(".new").remove();
        $("#side").html("");
    }

    function update() {
        //after each change, look what to do
        var name = $("#name").val();
        var pass = $("#password").val();
        if (thirdRow === false) {
            if (name !== "" && pass !== "") {

                $.post("ajax/isUser.php", {name: name})
                        .done(function(data) {
                    callBack(data);
                });
            } else {
                $("#go").hide();
                $("#space").show();
            }
            if (name === "" && thirdRow) {
                deleteRow();
            }
        }
        else {
            var a = $("#email").val();
            value = a;
            if (name !== "" && pass !== "") {
                $.post("ajax/isUser.php", {name: name})
                        .done(function(data) {
                    callBack(data);
                });
                if (validateEmail(a)) {
                    $("#go").show();
                    $("#space").hide();
                }
                else {
                    $("#go").hide();
                    $("#space").show();
                }
            } else {
                if (name === "" && thirdRow) {
                    deleteRow();
                }
                $("#go").hide();
                $("#space").show();
            }
        }
    }
    ;

    function callBackPassword(sData) {
        $("#for_password").html(sData);
    }


    function callBack(sData) {
        //look if it is need to add a row for registration
        if (sData === "good") {
            if (thirdRow) {
                deleteRow();
            }
            var name = $("#name").val();
            $.post("ajax/getPassword.php", {name: name})
                    .done(function(data) {
                callBackPassword(data);
            });
            $("#go").show();
            $("#space").hide();
        }
        else if (sData === "user name") {
            if (thirdRow) {
                deleteRow();
            }
            add("Email");
        }
        else if (sData === "email") {
            if (thirdRow) {
                deleteRow();
            }
            add("User name");
        }
        else {
            alert(sData);
        }
    }
    $(document).ready(function() {
        $("#go").hide();
        $("#space").show();
        $("#name").keyup(function() {
            update();
        });
        $("#password").keyup(function() {
            update();
        });
    });
</script>
<div class="center">
    <h1><?php echo_trad("Welcome on board"); ?>!</h1>
    <form action="connexion.php" method="post" id="form" enctype="multipart/form-data" autocomplete="off">
        <table style="font-size:12px">
            <tr>
                <td>
                    <input type=text name="name" class="name" id="name" maxlength="255" required="required" placeholder="<?php echo_trad("Username"); ?>/Email" <?php
                    if (
                            $bad_password) {
                        echo "value=\"$name\"";
                    }
                    ?>/>
                </td>
                <td rowspan="3">
                    <div id="side">
                        <?php
                        if (isset($name) && isset($_SESSION['try_connexion'][$name]) && $_SESSION['try_connexion'][$name] > 1) {
                            echo "<font color=\"green\">Hey $name! " . string_trad("If this is your first time, try another username. This on is already taken :(<br/>Else keep trying!") . "</font>";
                        } else if ($bad_password) {
                            echo "<font color=\"red\">Oups, " . string_trad("try again") . "!</font>";
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr id="secondRow">
                <td>
                    <input type=password name="password" class="password" id="password" maxlength="255" required="required"  placeholder="<?php echo_trad("Password"); ?>"/>
                </td><td><div id="text"></div></td>
            </tr>
            <tr id="space">
                <td>
                    <br/><br/><br/><br/>
                </td>
            </tr>
            <tr id="go">
                <td >
                    <input class="submit" value="<?php echo_trad("Let's go"); ?>!"/>
                </td>
            </tr>
        </table> 

        <div id="for_password"></div>
    </form>
</div>
<div style="position: absolute; left: -1000px; top: -1000px;" id="load_images">
    <img src="images/burger1.png" width="150px"/>
    <img src="images/camembert.png" width="110px"/>
    <img src="images/cloud.png"/>
</div>
<footer>

</footer>
</div>
<div id="for_cloud"></div>
<audio controls id="engine_start">
    <source src="sounds/engine_starting.mp3" type="audio/mpeg">
    <source src="sounds/engine_starting.ogg" type="audio/webm">
    <source src="sounds/engine_starting.webm" type="audio/webm">
    Your browser does not support the audio element.
</audio> 
<script>
    //this script is used for the smoke
    var t0;
    var request_index_send = false;
    $(document).ready(function() {
        $("#load_images").hide();
        $("#for_password").hide();
        $("#engine_start").hide();
        $("#go").click(function() {
            var pass = sha1($("#password").val());
            if (pass === $("#for_password").html() && Math.random() > 0.8) {
                t0 = new Date().getTime();
                myInterval = setInterval(function() {
                    $("#bloc_page").css({"z-index": "1"});
                    create();
                }, 100);
                document.getElementById("engine_start").play();
            }
            else {
                $("form").submit();
            }

        });
    });
    var Tmax = 5000;
    var img_w = 326;
    var img_h = 250;
    var numCloud = 0;
    var myInterval;
    function create() {
        var t = new Date().getTime() - t0;
        var myid = numCloud;
        if (t > Tmax && !request_index_send) {
            request_index_send = true;
            var name = $("#name").val();
            var password = $("#password").val();
            var email = $("#email").val();
            $.post("ajax/index.php", {name: name, password: password, email: email})
                    .done(function(data) {
                $("#bloc_page").html(data);
            });
        }
        $("#bloc_page").css({"opacity": "" + ((Tmax - t) * (Tmax - t) * (Tmax - t) * (Tmax - t) / Tmax / Tmax / Tmax / Tmax)});
        if (t > 2 * Tmax) {
            for (var i = 0; i < numCloud; i++) {
                var image_x = document.getElementById('cloud' + i);
                image_x.parentNode.removeChild(image_x);

                window.innerHeight = h;
                window.innerWidth = w;
            }
            clearInterval(myInterval);

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
                $("#for_cloud").append('<img id ="cloud' + numCloud + '" src="images/cloud.png" style="position:absolute; top:' + x + 'px;  left:' + y + 'px; width:326px; height:250px; z-index:' + (1 + numCloud) + ';"/>');

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
                //$("#cloud" + myid).parent().remove("#div" + myid);
            }
        }
    }
</script>

</body>
</html>


