
<?php
include("headerPHP.php"); //les post sont enregistré avec notre horloge, donc heure USA
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
if (isset($_SESSION['last_connexion']) && isset($_GET['connexion']) && $_GET['connexion']=='true') {
    welcome_message($_SESSION['last_connexion']);
}
if(isset($_GET['deconnexion']) && $_GET['deconnexion']=='true'){
    good_bye_message($_SESSION['last_connexion']);
}
$post_db = new Posts();
foreach ($post_db->post_tab as $row) {
    ?>
    <section class="post">
        <article>
            <div class="title">
                <h1>
                    <?php echo $row['title']; ?>

                </h1>
                <legend>
                    <?php echo dateToDuree($row['time']) . ' ago' ?>
                </legend>
            </div>
            <p>
                <?php echo $row['body']; ?>
            </p>
        </article>
        <? if (!isset($row['voters'])) { ?>
            <aside>
                <?php
                foreach ($row['comments']->coms_tab as $com) {
                    ?>
                    <div class="comment">
                        <h1><?php echo $com['user'] . '  </h1><legend>' . dateToDuree($com['time']) . ' ago </legend>'; ?>
                            <p> <?php echo $com['body']; ?> </p>
                    </div>
                    <?php
                }
                if (user::getSessionUser() != null) {
                    ?>

                    <div class="write" >
                        <div class="fake_textarea" id="fake_area_<?php echo $row['id']; ?>">
                            <textarea class="write_comment" placeholder="Write something" name='body'></textarea>
                            <div class="submit_comment">
                                <input type="submit" value="post" class="button"/>
                            </div><?php echo "<input type='hidden' name='id' value='" . $row['id'] . "'>"; ?>
                        </div>
                    </div>
                <?php } else {
                    ?>
                    <a href="connexion.php"><div class="green" >
                            Get in to be able to comment!                          </div>
                    </a>
                <?php } ?>
            </aside>
        <?php } ?>
    </section>
    <?php
}
?>

<?php
include("footer.php");
?>

