<?php

include("header.php");
if (isset($_POST['name'])) {
    $name = $_POST['name'];
    $email = !isset($_POST['email']) || $_POST['email'] == "" ? NULL : $_POST['email'];
    $password = sha1($_POST['password']);
    echo $name . " " . $email . " " . $password;
    // user::create($name, $password, $email);
    // header('Location: index.php');
}
?>
<script type="text/javascript">

    var thirdRow = false;

    function readData(sData) {
        if (sData === "good") {
            if (thirdRow) {
                thirdRow = false;
                var row = document.getElementById("new");
                row.parentNode.removeChild(row);
            }
            $("#go").show();
        }
        if (sData === "user name") {
            if (!thirdRow) {
                thirdRow = true;
                var newRow = $('<tr id="new"> <td><input type=text name="email" class="email" id="email" maxlength="255" placeholder="Email"/></td><td><font color="green">You don\'t seem to be registered yet, please provide an email adress</font></td></tr>');
                $("#password").after(newRow);
                $("#email").keyup(function() {
                    var name = $("#name").val();
                    var pass = $("#password").val();
                    var a = $("#email").val();
                    if (name !== "" && pass !== "" && a !== "") {
                        $("#go").show();
                    }
                    else {
                        $("#go").hide();
                    }
                });
            }
        }
        if (sData === "email") {
            if (!thirdRow) {
                thirdRow = true;
                var newRow = $('<tr id="new"> <td><input type=text name="email" class="email" id="email" maxlength="255" placeholder="User Name"/></td><td><font color="green">You don\'t seem to be registered yet, please provide a user name</font></td></tr>');
                $("#password").after(newRow);
                $("#email").keyup(function() {
                    var name = $("#name").val();
                    var pass = $("#password").val();
                    var a = $("#email").val();
                    if (name !== "" && pass !== "" && a !== "") {
                        $("#go").show();
                    }
                    else {
                        $("#go").hide();
                    }
                });
            }
        }
    }

    $(document).ready(function() {
        $("#go").hide();
        $("#new").hide();
        $("#name").keyup(function() {
            var name = $("#name").val();
            var pass = $("#password").val();
            var a = thirdRow === false ? "a" : $("#email").val();
            if (name !== "" && pass !== "" && a !== "") {
                request(readData, 'ajax/isUser.php', name);
            }
            else {
                $("#go").hide();
            }
        });
        $("#password").keyup(function() {
            var name = $("#name").val();
            var pass = $("#password").val();
            var a = thirdRow === false ? "a" : $("#email").val();
            if (name !== "" && pass !== "" && a !== "") {
                request(readData, 'ajax/isUser.php', name);
            }
            else {
                $("#go").hide();
            }
        });
    });
</script>

<div class="center">
    <h1>Welcome on board!</h1>
    <form action="connexion.php" method="post" enctype="multipart/form-data" autocomplete="off">

        <table style="font-size:12px">
            <tr>
                <td>
                    <input type=text name="name" class="name" id="name" maxlength="255" required="required" placeholder="Username/Email"/>
                </td>
                <td><div id="text"></div></td>
            </tr>
            <tr id="secondRow">
                <td>
                    <input type=password name="password" class="password" id="password" maxlength="255" required="required"  placeholder="Password"/>
                </td>
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


