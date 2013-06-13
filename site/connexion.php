<?php
include("headerPHP.php");

$bad_password = false;
if (isset($_POST['name'])) {
    $name = htmlspecialchars($_POST['name']);
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

    var thirdRow = false;
    var value = "";
    var changedEmail = false;

    function check(type) {
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
        //used for ajax
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
        thirdRow = false;
        var row = document.getElementById("new");
        row.parentNode.removeChild(row);
        $("#side").html("");
    }

    function update() {

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


    function callBack(sData) {
        //used for ajax
        if (sData === "good") {
            if (thirdRow) {
                deleteRow();
            }
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
        $("#go").hide();
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
    <form action="connexion.php" method="post" enctype="multipart/form-data" autocomplete="off">
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
                        if (
                                $bad_password) {
                            echo "<font color=\"red\">Oups, it seems you are too high to remember you password. Try again!</font>";
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
                    <input type=submit class="submit" value="Let's go!"/>
                </td>
            </tr>
        </table> 
    </form>
</div>

<?php
include("footer.php");
?>


