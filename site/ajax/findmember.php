<?php
include("../headerPHP.php");
$user = user::getSessionUser();
if ($user != null && $user->type == 2) {
    ?>
    <div>
        <br/><br/><br/>users:
        <br/></div>
    <?php
    $name = (isset($_POST["name"])) ? htmlspecialchars($_POST["name"]) : "";
    $email = (isset($_POST["email"])) ? htmlspecialchars($_POST["email"]) : "";

    $i = 0;
    if ($name OR $email) {

        $dbh = Database::connect();
        $query = "SELECT * FROM `user` WHERE `name` LIKE '%$name%' AND `email` LIKE '%$email%'";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'user');
        $sth->execute();
        while ($user = $sth->fetch()) {
            $i = $i + 1;
            $name = $user->name;
            $email = $user->email;
            $type = $user->type;
            $email=$user->email;
            $id = $user->id;
            ?>
            <div class="user">

                <form action="modifyMemberStatus.php" method="post"> <table>
                        <tr>
                            <td>name : </td>
                            <td><?php echo $name; ?></td>
                        </tr>
                        <tr>
                            <td>email : </td>
                            <td><?php echo $email; ?></td>
                        </tr>
                        <tr>
                            <td>first connection : </td>
                            <td><?php echo $user->first_connexion; ?></td>
                        </tr>
                        <tr>
                            <td>last connection : </td>
                            <td><?php echo $user->last_connexion; ?></td>
                        </tr>
                        <tr>
                            <td>nb connections : </td>
                            <td><?php echo $user->num_connexion; ?></td>
                        </tr>
                        <tr>
                            <td>status : </td>
                            <td>regular</td><td><input type="radio" name="status" value="0" <?php if ($type == 0) { ?>checked="checked"<?php } ?>/></td>
                        </tr>
                        <tr><td></td><td>friend</td><td><input type="radio" name="status" value="1" <?php if ($type == 1) { ?>checked="checked"<?php } ?>/></td>
                        </tr>
                        <tr><td></td><td>admin</td><td><input type="radio" name="status" value="2" <?php if ($type == 2) { ?>checked="checked"<?php } ?>/></td>
                        </tr>
                    </table>
                    <input type="hidden" name="id" value="<?php echo $id ?>"/>
                    <input type="submit" value="Modify"/>
                </form>
            </div>'

            <?php
        }
        $sth->closeCursor();
        $dbh = null;
    } else {
        $i = -1;
        $dbh = Database::connect();
        $query = "SELECT * FROM `user` ORDER By first_connexion DESC";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'user');
        $sth->execute();
        while ($user = $sth->fetch()) {
            $name = $user->name;
            $type = $user->type;
            $email = $user->email;
            $id = $user->id;
            ?>
            <div class="user">

                <form action="modifyMemberStatus.php" method="post"> <table>
                        <tr>
                            <td>name : </td>
                            <td><?php echo $name; ?></td>
                        </tr>
                        <tr>
                            <td>email : </td>
                            <td><?php echo $email; ?></td>
                        </tr>
                        <tr>
                            <td>first connection : </td>
                            <td><?php echo $user->first_connexion; ?></td>
                        </tr>
                        <tr>
                            <td>last connection : </td>
                            <td><?php echo $user->last_connexion; ?></td>
                        </tr>
                        <tr>
                            <td>nb connections : </td>
                            <td><?php echo $user->num_connexion; ?></td>
                        </tr>
                        <tr>
                            <td>status : </td>
                            <td>regular</td><td><input type="radio" name="status" value="0" <?php if ($type == 0) { ?>checked="checked"<?php } ?>/></td>
                        </tr>
                        <tr><td></td><td>friend</td><td><input type="radio" name="status" value="1" <?php if ($type == 1) { ?>checked="checked"<?php } ?>/></td>
                        </tr>
                        <tr><td></td><td>admin</td><td><input type="radio" name="status" value="2" <?php if ($type == 2) { ?>checked="checked"<?php } ?>/></td>
                        </tr>
                    </table>
                    <input type="hidden" name="id" value="<?php echo $id ?>"/>
                    <input type="submit" value="Modify"/>
                </form>
            </div>'

            <?php
        }
        $sth->closeCursor();
        $dbh = null;
    }
    ?>
    <style type="text/css">
        .mot {
            background-color:#FFFF00;
            font-weight: bold;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            if (<?php echo $i; ?> === 0) {
                $("#entete").html('<br/><br/><br/>Aucun résultat ne correspond à votre recherche.<br/>');
            }
            if (<?php echo $i; ?> === -1) {
                $("#entete").html('<br/><br/><br/>Liste des membres :<br/>');
            }
            if (<?php echo $i; ?> > 0) {
                $("#entete").html('<br/><br/><br/><?php echo $i; ?> Résultats trouvés :<br/>');
            }
        });
    </script>
<?php } ?>