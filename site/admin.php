<?php
include("headerPHP.php");
$user = user::getSessionUser();
if ($user != null && $user->type == 2) {
    htmlHeader("blog");
    ?>

    <div>
        Look for a member : <br/><br/>
        <form action="index.php?page=admin/utilisateurs" method="post">
            <table>
                <tr><td>user name</td><td><input id="name" type="text" name="name"/></td></tr>
                <tr><td>email</td><td><input id="email" type="text" name="email"/></td></tr>
            </table>
        </form>

        <div id="divutilisateur"></div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            var name = $("#name").val();
            var email = $("#email").val();
            $.post("ajax/findmember.php", {name: name, email: email})
                    .done(function(data) {
                $("#divutilisateur").html(data);
            });
            $("#name").keyup(function() {
                name = $("#name").val();
                email = $("#email").val();
                $.post("ajax/findmember.php", {name: name, email: email})
                        .done(function(data) {
                    $("#divutilisateur").html(data);
                });
            });
            $("#email").keyup(function() {
                name = $("#name").val();
                email = $("#email").val();
                $.post("ajax/findmember.php", {name: name, email: email})
                        .done(function(data) {
                    $("#divutilisateur").html(data);
                });
            });
        });
    </script>
    <?php
}else{
      header('Location: index.php');
}
?>
    