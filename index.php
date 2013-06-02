
<?php
include("header.php");
?>
<?php
$post_db = new Posts();
foreach ($post_db->post_tab as $row) {
    ?> <section class="post">
        <article>
            <h1><img src="images/ico_epingle.png" alt="Catégorie voyage"
                     class="ico_categorie" /><?php echo $row[4]; ?></h1>
            <legend>by user the <?php $row[2] ?></legend>
            <p><?php echo $row[7]; ?></p>
        </article>
        <aside>
            <?php
            $post_db = new Posts();
            foreach ($post_db->post_tab as $row) {
                ?> <section class="post">
                    <article>
                        <h1><img src="images/ico_epingle.png" alt="Catégorie voyage"
                                 class="ico_categorie" /><?php echo $row['title']; ?></h1>
                        <legend>by user the <?php $row['time'] ?></legend>
                        <p><?php echo $row['body']; ?></p>
                    </article>
                    <aside>
                        <?php
                        foreach ($row['comments']->coms_tab as $com) {
                            ?>
                            <div class="comment">
                                <h1><?php echo $com['user'] . ', the ' . $com['time']; ?> </h1>
                                <p> <?php echo $com['body']; ?> </p>
                            </div>
                            <?php
                        }
                        ?>

                        <div class="write">
                            <div class="fake_textarea">
                                <textarea class="write_comment" placeholder="write something"></textarea>
                                <div class="submit_comment">
                                    <input type="submit" value="post"/>
                                </div>
                            </div>
                        </div>
                    </aside>
                </section>
                <?php
            }
            ?>

            <div class="write">
                <div class="fake_textarea">
                    <textarea class="write_comment" placeholder="write something"></textarea>
                    <div class="submit_comment">
                        <input type="submit" value="post"/>
                    </div>
                </div>
            </div>
        </aside>
    </section>
    <?php
}
?>

<section class="post">
    <article>
        <h1>Vive l'aventure et la programation !</h1>
        <legend>by guigui the 6.1 at 21:10</legend>
        <p>Je suis un geek amoureux de mon van ;)</p>
    </article>

    <aside>
        <div class="comment">
            <h1>Funny fact</h1>
            <p>
                C'est la class, on geek un max n'est ce pas?
            </p>
            <p> 
                Ce blog sera alimente par mes fautes d'orthographe et mon nouveau
                Nexus4.... yeah yeah :)
            </p>
        </div>
        <div class="comment">
            <h1>Comment #2</h1>
            <p>
                C'est la class, on geek un max n'est ce pas?
            </p>
        </div>
        <div class="write">
            <div class="fake_textarea">
                <textarea class="write_comment" placeholder="write something"></textarea>
                <div class="submit_comment">
                    <input type="submit" value="post"/>
                </div>
            </div>
        </div>
    </aside>
</section>

<section class="post">
    <article>
        <h1>Paint your own car for under $200</h1>
        <legend>by guigui the 6.1 at 20:45</legend>
        <p>I have a 1971 VW Westfalia camper bus. It was in bad need of a paint job when I got it. Someone had painted it with what I believe to be house paint. No bueno :( I decided I had to take care of it. I figured I would do it cheaply, but I wanted it to not be an embarassment either. The reason I was so interested in d.i.y. and cheap is because I want to take it camping but not stress about scratches etc. I wasnt shooting for show-quality paint here.

        </p><p>   After some googling I came across 2 really good articles on painting your own vehicle with "Rustoleum", so I figured I was game. The two sites are:

        </p><p>   The $50 Paint Job  </p>
        <p>   A Cheapskate’s Paint Job </p>
    </article>

    <aside>
        <div class="write">
            <div class="fake_textarea">
                <textarea class="write_comment" placeholder="write something"></textarea>
                <div class="submit_comment">
                    <input type="submit" value="post"/>
                </div>
            </div>
        </div>
    </aside>
</section>

<?php
include("footer.php");
?>

