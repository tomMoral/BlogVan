<?php

include("header.php");
if (isset($_POST['name'])) {
    $name = $_POST['name'];
    $email = $_POST['email'] == "" ? NULL : $_POST['email'];
    $password = sha1($_POST['password']);
    user::create($name, $password, $email);
    header('Location: index.php');
}
?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#go").hide();
        $("#name").keyup(function() {
            var name = $("#name").val();
            var pass = $("#password").val();
            if (name !== "" && pass !== "") {
                $("#go").show();
            }
        });
        $("#password").keyup(function() {
            var name = $("#name").val();
            var pass = $("#password").val();
            if (name !== "" && pass !== "") {
                $("#go").show();
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
            </tr>
            <tr>
                <td>
                    <input type=password name="password" class="password" id="password" maxlength="255" required="required"  placeholder="Password"/>
                </td>
            </tr>
            <tr>
                <td>
                    <input type=text name="email" class="email" id="email" maxlength="255" placeholder="Email"/>
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


