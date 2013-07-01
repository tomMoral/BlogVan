
<?php
include_once("headerPHP.php"); //les post sont enregistré avec notre horloge, donc heure USA
htmlHeader("blog");
$user = user::getSessionUser();
?>
<script>
    $(document).ready(function() {
        $('textarea').autosize();
        $('.submit_comment').hide();
        set_text_area_background_color();
        new_comment();
    });
</script>
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
$post_db = new Posts();
    if ($user == null) {
        $language = isset($_SESSION['language']) ? $_SESSION['language'] == "FR" ? "FR" : "EN" : "EN";
    } else {
        $language = $user->language == "FR" ? "FR" : "EN";
    }
foreach ($post_db->post_tab as $row) {
    if ($row['permission'] != 0 || ($user != null && $user->type != 0)) {
        ?>
        <section class="post">
            <article>
                <div class="title">
                    <h1>
                        <?php echo $language=="FR" ? $row['title_french']:$row['title']; ?>

                    </h1>
                    <legend>
                        <?php echo dateToDuree($row['time'])?>
                    </legend>
                </div>
                <div class="body_post">
                    <p>
                        <?php echo $language == "FR" ? $row['body_french']:$row['body']; ?>
                    </p></div>
            </article>
            <aside>
                <?php
                foreach ($row['comments']->coms_tab as $com) {
                    ?>
                    <div class="comment">
                        <h1><?php echo $com['user'] . '  </h1><legend>' . dateToDuree($com['time']) . ' </legend>'; ?>
                            <p> <?php echo $com['body']; ?> </p>
                    </div>
                    <?php
                }
                if (user::getSessionUser() != null) {
                    ?>

                    <div class="write" >
                        <div class="fake_textarea" id="fake_area_<?php echo $row['id']; ?>">
                            <textarea class="write_comment" placeholder="<?php echo_trad("Write something"); ?>" name='body'></textarea>
                            <div class="submit_comment">
                                <input type="submit" value="post" class="button"/>
                            </div><?php echo "<input type='hidden' name='id' value='" . $row['id'] . "'>"; ?>
                        </div>
                    </div>
                <?php } else {
                    ?>
                    <a href="connexion.php"><div class="green" >
                            <?php echo_trad("Get in to be able to comment");?>!                          </div>
                    </a>
                <?php } ?>
            </aside>
        </section>
        <?php
    }
}
?>

<?php
include_once("footer.php");
?>

