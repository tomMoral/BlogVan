
<?php

include_once("headerPHP.php"); //les post sont enregistré avec notre horloge, donc heure USA
htmlHeader("blog");
$user = user::getSessionUser();
?>
<?php

if (isset($_SESSION['last_connexion']) && isset($_GET['connexion']) && $_GET['connexion'] == 'true') {
    welcome_message($_SESSION['last_connexion']);
}
if (isset($_SESSION['last_connexion']) && isset($_SESSION['connexion']) && $_SESSION['connexion'] == true) {
    welcome_message($_SESSION['last_connexion'], true);
    $_SESSION['connexion'] = null;
}
if (isset($_GET['deconnexion']) && isset($_SESSION['last_user']) && $_GET['deconnexion'] == 'true') {
    good_bye_message($_SESSION['last_user']);
}
if (isset($_GET['firstconnexion']) && $_GET['firstconnexion'] == 'true') {
    echo "<div class='welcome'><div id='div1'>";
    echo "Welcome in our blog " . $user->name . "!";
    echo "</div><div id='div2'><img src='../images/6.png'/></div></div>";
}
?>
<script>
    $(document).ready(function() {
        $.post("ajax/load_post.php", {last_id: -1})
                .done(function(data) {
            $("#bloc_page").append(data);
        });
    });
</script>
<?php

include_once("footer.php");
?>

