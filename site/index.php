
<?php
include("headerPHP.php");
date_default_timezone_set('America/Los_Angeles'); //les post sont enregistré avec notre horloge, donc heure USA
htmlHeader("blog");
?>
<script>
    $(document).ready(function() {
        $('textarea').autosize();
        $('.submit_comment').hide();
        set_text_area_background_color();
    });
</script>
<div id="banniere_image">
    <div id="banniere_description">
        Blog du VW bus...
        <a href="#" class="bouton_rouge">
            Voir l'article 
            <img src="../images/flecheblanchedroite.png" alt="" />
        </a>
    </div>
</div>
<?php
$post_db = new Posts();
foreach ($post_db->post_tab as $row) {
    ?>
    <section class="post">
        <article>
            <h1><?php echo $row['title']; ?></h1>
            <legend><?php echo dateToDuree($row['time']) . ' ago' ?></legend>
            <p><?php echo $row['body']; ?></p>
        </article>
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
            ?>

            <div class="write" >
                <form action="new_comment.php" method="post">
                    <div class="fake_textarea">
                        <textarea class="write_comment" placeholder="Write something" name='body'></textarea>
                        <div class="submit_comment">
                            <input type="submit" value="post"/>
                        </div><?php echo "<input type='hidden' name='id' value='" . $row['id'] . "'>"; ?>
                        
                    </div>
                </form>
            </div>
        </aside>
    </section>
    <?php
}
?>

<?php
include("footer.php");
?>

