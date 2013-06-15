<?php
include("headerPHP.php");
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
    $pos = strrpos($name, '@');
    $type = ($pos === false) ? 'name' : 'email';

    if ($type == 'name') {
        $user = user::getByName($name);
        if ($user == null) {
            //then create user
            $email = htmlspecialchars($_POST['email']);
            user::create($name, $password, $email);
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
            user::create($email, $password, $name);
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
            $("#side").html('<font color="red">Someone is already using this ' + type + ', try an other one!</font>');
            $("#go").hide();
            changedEmail = true;
        }
        else if (sData !== "user name" && sData !== "email") {
            alert(sData);
        }
        else {
            if (changedEmail) {
                $("#side").html('<font color="green">This ' + type + ' is available!</font>');
            } else {
                $("#side").html('');
            }
        }
    }

    function add(label) {
        //if needed, add a row for inscription
        thirdRow = true;
        str = '<tr id="new"> <td><input type=text name="email" class="email" id="email" maxlength="255" placeholder="' + label + '" value="' + value + '"/></td>';
        if (label === "Email") {
            $("#side").html('<font color="green">You don\'t seem to be registered yet, please provide an email adress</font>');
        } else {
            $("#side").html('<font color="green">You don\'t seem to be registered yet, please provide a user name</font>');
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
            }
            else {
                $("#go").hide();
            }
        });
    }

    function deleteRow() {
        //if we don't need the row anymore, we delete it
        thirdRow = false;
        var row = document.getElementById("new");
        row.parentNode.removeChild(row);
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
                }
                else {
                    $("#go").hide();
                }
            } else {
                if (name === "" && thirdRow) {
                    deleteRow();
                }
                $("#go").hide();
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
        // $("#go").hide();
        $("#name").keyup(function() {
            update();
        });
        $("#password").keyup(function() {
            update();
        });
    });
</script>
<div class="center">
    <h1>Welcome on board!</h1>
    <form action="connexion.php" method="post" id="form" enctype="multipart/form-data" autocomplete="off">
        <table style="font-size:12px">
            <tr>
                <td>
                    <input type=text name="name" class="name" id="name" maxlength="255" required="required" placeholder="Username/Email" <?php
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
                            echo "<font color=\"green\">Hey $name! If this is your first time, try another username. This on is already taken :(</font>";
                        } else if ($bad_password) {
                            echo "<font color=\"red\">Oups, try again!</font>";
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr id="secondRow">
                <td>
                    <input type=password name="password" class="password" id="password" maxlength="255" required="required"  placeholder="Password"/>
                </td><td><div id="text"></div></td>
            </tr>
            <tr id="go">
                <td >
                    <div>Let's go!</div><input type=submit class="submit" value="Let's go!"/>
                </td>
            </tr>
        </table> 
        <audio controls id="engine_start">
            <source src="../sounds/engine_starting.mp3" type="audio/mpeg">
            <source src="../sounds/engine_starting.ogg" type="audio/webm">
            <source src="../sounds/engine_starting.webm" type="audio/webm">
            Your browser does not support the audio element.
        </audio> 
        <div id="for_password"></div>
    </form>
</div>
<div id="for_cloud"></div>
<script>
    //this script is used for the smoke
    var t0;
    $(document).ready(function() {
        //  $("#for_password").hide();
        $("#engine_start").hide();
        $("#go").click(function() {
            var pass = sha1($("#password").val());
            //if (pass === $("#for_password").html()) {
            if (true) {
                t0 = new Date().getTime();
                myInterval = setInterval(function() {
                    $("#bloc_page").css({"z-index": "1"});
                    create();
                }, 100);
                document.getElementById("engine_start").play();
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
        if (myid === 1 && t > Tmax) {
            alert("test");
            $.post("ajax/index.php")
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
                $("body").append('<img id ="cloud' + numCloud + '" src="../images/cloud.png" style="position:absolute; top:' + x + 'px;  left:' + y + 'px; width:326px; height:250px; z-index:' + (-1000 + numCloud) + ';"/>');

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
<?php
include("footer.php");
?>


