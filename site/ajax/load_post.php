<?php
include("../headerPHP.php");
$user = user::getSessionUser();
if (isset($_POST['last_id']) || true) {
    $post_db = new Posts();
    if ($user == null) {
        $language = isset($_SESSION['language']) ? $_SESSION['language'] == "FR" ? "FR" : "EN"  : "EN";
    } else {
        $language = $user->language == "FR" ? "FR" : "EN";
    }
    $found_next = false;
    foreach ($post_db->post_tab as $row) {
        if (!$found_next && ($_POST['last_id'] == -1 || $_POST['last_id'] > $row['id'])) {
            $found_next = true;
            if ($row['permission'] != 0 || ($user != null && $user->type != 0)) {
                ?>
                <section class="post">
                    <article>
                        <div class="title">
                            <h1>
                                <?php echo $language == "FR" ? $row['title_french'] : $row['title']; ?>

                            </h1>
                            <legend>
                                <?php echo dateToDuree($row['time']) ?>
                            </legend>
                        </div>
                        <div class="body_post">
                            <?php echo $language == "FR" ? $row['body_french'] : $row['body']; ?>
                            
                        </div>
                        
                        <?php if ($user != null && $user->type == 2) { ?>
                            <div class="delete_post" id="delete_post_<?php echo $row['id']; ?>">
                                <a class="delete_post_a"href="#"><?php echo_trad("delete"); ?></a>
                            </div>
                            <?php if ($row['vote'] != '' && $row['vote'][0] != 'c'){?>
                            <div class="close_post" id="close_post_<?php echo $row['id']; ?>">
                                <a class="close_post_a"href="#"><?php echo_trad("close"); ?></a>
                            </div>
                            <?php }} ?>
                    </article>
                    <aside>
                        <?php
                        foreach ($row['comments']->coms_tab as $com) {
                            ?>
                            <div class="comment">
                                <h1><?php echo $com['user'] . '  </h1><legend>' . dateToDuree($com['time']) . ' </legend>'; ?>
                                    <p> <?php echo $com['body']; ?> </p>
                                    <?php if($user!=null && ($user->type==2 || $user->name==$com['user'])){?>
                                        <div class="delete_comment" id="delete_comment_<?php echo $com['id']; ?>">
                                            <a class="delete_comment_a"href="#">(<?php echo_trad("delete"); ?>)</a>
                                        </div>
                                        <?php }?>
                            </div>
                            <?php
                        }
                        if ($user != null) {
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
                                    <?php echo_trad("Get in to be able to comment"); ?>!                          </div>
                            </a>
                        <?php } ?>
                    </aside>
                </section>
                <script>
                    $(document).ready(function() {
                        $.post("ajax/load_post.php", {last_id: <?php echo $row['id']; ?>})
                                .done(function(data) {
                            $("#bloc_page").append(data);
                        });
                    });
                </script>
                <script>
                    $(document).ready(function() {
                        $('textarea').autosize();
                        $('.submit_comment').hide();
                        set_text_area_background_color();
                        new_comment(<?php echo $row['id']; ?>);
                    });
                </script>
                <script>$(document).ready(function() {
                        $("#delete_post_<?php echo $row['id']; ?>").click(function() {
                            var answer = confirm("Are you sure?");
                            if (answer) {
                                window.location = "index.php?delete_post=<?php echo $row['id']; ?>";
                            }
                            else {
                            }
                        });
                        $("#close_post_<?php echo $row['id']; ?>").click(function() {
                            var answer = confirm("Are you sure?");
                            if (answer) {
                                window.location = "index.php?close_post=<?php echo $row['id']; ?>";
                            }
                            else {
                            }
                        });
                        $(".delete_comment").click(function() {
                            var answer = confirm("Are you sure?");
                            if (answer) {
                                window.location = "index.php?delete_comment="+$(this).attr("id").split("_")[2];
                            }
                            else {
                            }
                        });
                    });
                </script>
                         <?php
            }
        }
    }
}
?>
