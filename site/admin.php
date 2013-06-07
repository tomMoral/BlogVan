<?php
include("headerPHP.php");
htmlHeader("blog");
$user = user::getSessionUser();
if ($user->type == 2) {
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
            var champs = new Array;
            champs[0] = 'name';
            champs[1] = 'email';
            requestMultiFields(readData, 'ajax/findmember.php', champs);
            $("#name").keyup(function() {
                requestMultiFields(readData, 'ajax/findmember.php', champs);
            });
            $("#email").keyup(function() {
                requestMultiFields(readData, 'ajax/findmember.php', champs);
            });
        });

        function readData(sData) {
            $("#divutilisateur").html(sData);
        }
    </script>



    <?php
}
?>
    