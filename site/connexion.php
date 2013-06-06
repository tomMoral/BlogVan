<?php
include("headerPHP.php");

$bad_password = false;
if (isset($_POST['name'])) {

    echo "connexion...";
    $name = $_POST['name'];
    $password = sha1($_POST['password']);
    $pos = strrpos($name, '@');
    $type = ($pos === false) ? 'name' : 'email';

    if ($type == 'name') {
        echo "Name..";
        $user = user::getByName($name);
        if ($user == null) {
            //then create user
            $email = $_POST['email'];
            user::create($name, $password, $email);
            header('Location: index.php?firstconnexion=true');
        } else {

            echo "User..";
            //then identify user
            if($user->loginByName($name, $password)){
                echo "TRue";
            }else{
                echo "Bad";
                $bad_password=true;
            }
        }
    } else {
        //similar to first case
        $user = user::getByEmail($name);
        if ($user == null) {
             //then create user
            $email = $_POST['email'];
            user::create($email, $password, $name);
            header('Location: index.php?firstconnexion=true');
        } else {
            //then identify user
            if($user->loginByEmail($name, $password)){
            }else{
                $bad_password=true;
            }
        }
    }
} 
htmlHeader("connexion");
?>
<script type="text/javascript">

    var thirdRow = false;
    var value = "";

    function add(label) {
        thirdRow = true;
        str = '<tr id="new"> <td><input type=text name="email" class="email" id="email" maxlength="255" placeholder="' + label + '" value="' + value + '"/></td>';
        if (label === "Email") {
            str += '<td><font color="green">You don\'t seem to be registered yet, please provide an email adress</font></td></tr>';
        } else {
            str += '<td><font color="green">You don\'t seem to be registered yet, please provide a user name</font></td></tr>';
        }
        var newRow = $(str);
        $("#secondRow").after(newRow);
        $("#email").keyup(function() {
            var name = $("#name").val();
            var pass = $("#password").val();
            var a = $("#email").val();
            if (name !== "" && pass !== "" && validateEmail(a)) {
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
    }

    function update() {

        var name = $("#name").val();
        var pass = $("#password").val();
        if (thirdRow === false) {
            if (name !== "" && pass !== "") {
                request(readData, 'ajax/isUser.php', name);
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
                alert("here1");
                request(readData, 'ajax/isUser.php', name);
                if (validateEmail(a)) {
                    $("#go").show();
                alert("here t");
                }
                else {
                    $("#go").hide();
                alert("here f");
                }
            } else {
                if (name === "" && thirdRow) {
                    deleteRow();
                }
                $("#go").hide();
            }
        }

    };


    function readData(sData) {
        //used for ajax
        alert(sData);
        if (sData === "good") {
            if (thirdRow) {
                deleteRow();
            }
            $("#go").show();
        }
        if (sData === "user name") {
            if (thirdRow) {
                deleteRow();
            }
            add("Email");
        }
        if (sData === "email") {
            if (thirdRow) {
                deleteRow();
            }
            add("User name");
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
                    <input type=text name="name" class="name" id="name" maxlength="255" required="required" placeholder="Username/Email" <?php if(
                $bad_password){echo "value=\"$name\"";}?>/>
                </td>
                <td><div id="bad_password"></div>
<?php if(
                $bad_password){echo "<font color=\"red\">Oups, it seems you are too high to remember you password. Try again!</font>";}?></td>
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


