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
                $I_found_this_cool = false;
                foreach (explode(",", $row['like']) as $user_id) {
                    if ($user_id == $user->id . "") {
                        $I_found_this_cool = true;
                    }
                }
                $nb_who_liked = $row['like'] == "" ? 0 : count(explode(",", $row['like']));
                ?>
                <section class="post" >
                    <article id="article_<?php echo $row['id']; ?>">
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
                        <div class="like_post<?php echo $I_found_this_cool ? "_already_liked" : "\" id=\"like_post_" . $row['id']; ?>">
                            <img src='images/peace.png' width='40px'/><div class="text_center"><?php echo_trad($I_found_this_cool ? "you find it really cool!" : "this is really cool!"); ?> <?php if ($nb_who_liked) echo"($nb_who_liked)"; ?></div>
                       </div>
                        <?php if ($user != null && $user->type == 2) { ?>
                            <div class="delete_post">
                                <a class="delete_post_a"href="modify_post.php?id_modify=<?php echo $row['id']; ?>"><?php echo_trad("modify"); ?></a>
                                <a class="delete_post_a"href="#" id="delete_post_<?php echo $row['id']; ?>"><?php echo_trad("delete"); ?></a>
                            </div>
                            <?php if ($row['vote'] != '' && $row['vote'][0] != 'c') { ?>
                                <div class="close_post" id="close_post_<?php echo $row['id']; ?>">
                                    <a class="close_post_a"href="#"><?php echo_trad("close"); ?></a>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </article>
                    <aside id="asside_<?php echo $row['id']; ?>">
                        <?php
                        foreach ($row['comments']->coms_tab as $com) {
                            ?>
                            <div class="comment">
                                <h1><?php echo $com['user'] . '  </h1><legend>' . dateToDuree($com['time']) . ' </legend>'; ?>
                                    <p> <?php echo $com['body']; ?> </p>

                                    <?php if ($user != null && ($user->type == 2 || $user->name == $com['user'])) { ?>
                                        <div class="delete_comment" id="delete_comment_<?php echo $com['id']; ?>">
                                            <a class="delete_comment_a"href="#">(<?php echo_trad("delete"); ?>)</a>
                                        </div>
                                    <?php } ?>
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
                //manage the like
                    $(document).ready(function() {
                        var nbUserWhoLiked = <?php echo $nb_who_liked; ?>;
                        $("#like_post_<?php echo $row['id']; ?>").click(function() {
                            $("#like_post_<?php echo $row['id']; ?> .text_center").html("<?php echo_trad("you find it really cool!"); ?> (" + (nbUserWhoLiked + 1) + ")");
                            $("#like_post_<?php echo $row['id']; ?>").hover(function() {
                                $(this).css("cursor", "default");
                            });
                            $("#like_post_<?php echo $row['id']; ?> .text_center").attr("id", "like_post_<?php echo $row['id']; ?>_already_liked");
                            $.post("ajax/like_post.php", {post_id: <?php echo $row['id']; ?>})
                                    .done(function(data) {
                            });

                        });
                    });
                </script>
                <script>
                    //manage the comment size
                    function toogle(id, i, iMax, order) {
                        if (i < iMax) {
                            var j = order ? i : iMax - i - 1;

                            $("#asside_" + id + " .comment:eq(" + j + ")").toggle(600, function() {
                                i++;
                                toogle(id, i, iMax, order);
                            });
                        }
                    }
                    $(document).ready(function() {
                        var disp_all = true;
                        var hMax<?php echo $row['id']; ?> = parseInt($("#article_<?php echo $row['id']; ?>").css("height").split('px')[0]);
                        var h<?php echo $row['id']; ?> = parseInt($("#asside_<?php echo $row['id']; ?>").css("height").split('px')[0]);
                        var nbCommentsIni<?php echo $row['id']; ?> =<?php echo count($row['comments']->coms_tab); ?>;
                        var nbCommentstoHide<?php echo $row['id']; ?> = 0;
                        var nbComments<?php echo $row['id']; ?> = nbCommentsIni<?php echo $row['id']; ?>;
                        if (nbComments<?php echo $row['id']; ?> > 1 && hMax<?php echo $row['id']; ?> + 200 < h<?php echo $row['id']; ?>) {
                            $("#asside_<?php echo $row['id']; ?>").prepend("<div class=\"display_comment\"><?php echo_trad("show all comments"); ?></div>");

                            var i = 0;
                            while (nbComments<?php echo $row['id']; ?> > 1 && hMax<?php echo $row['id']; ?> + 100 < h<?php echo $row['id']; ?>) {
                                nbComments<?php echo $row['id']; ?>--;
                                $("#asside_<?php echo $row['id']; ?> .comment:eq(" + i + ")").hide();
                                i++;
                                h<?php echo $row['id']; ?> = parseInt($("#asside_<?php echo $row['id']; ?>").css("height").split('px')[0]);
                                disp_all = false;
                            }
                            nbCommentstoHide<?php echo $row['id']; ?> = nbCommentsIni<?php echo $row['id']; ?> - nbComments<?php echo $row['id']; ?>;
                            nbComments<?php echo $row['id']; ?> = nbCommentsIni<?php echo $row['id']; ?>;
                        }
                        $("#asside_<?php echo $row['id']; ?> .display_comment").click(function() {
                            if (disp_all) {
                                $(this).html("<?php echo_trad("show all comments"); ?>");
                                var i = 0;
                                toogle(<?php echo $row['id']; ?>, 0, nbCommentstoHide<?php echo $row['id']; ?>, true);
                                disp_all = false;
                            } else {
                                $(this).html("<?php echo_trad("hide comments"); ?>");
                                var i = 0;
                                toogle(<?php echo $row['id']; ?>, 0, nbCommentstoHide<?php echo $row['id']; ?>, false);
                                disp_all = true;
                            }
                        });
                    });
                </script>
                <script>
                    //load next post
                    $(document).ready(function() {
                        $.post("ajax/load_post.php", {last_id: <?php echo $row['id']; ?>})
                                .done(function(data) {
                            $("#bloc_page").append(data);
                        });
                    });</script>
                <script>
                    //auto size the text area
                    $(document).ready(function() {
                        $('textarea').autosize();
                        $('.submit_comment').hide();
                        set_text_area_background_color();
                        new_comment(<?php echo $row['id']; ?>);
                    });</script>
                <script>
                    //manage the deletions
                    $(document).ready(function() {
                        $("#delete_post_<?php echo $row['id']; ?>").click(function() {
                            var answer = confirm("Are you sure you want to delete this post?");
                            if (answer) {
                                window.location = "index.php?delete_post=<?php echo $row['id']; ?>";
                            }
                            else {
                            }
                        });
                        $("#close_post_<?php echo $row['id']; ?>").click(function() {
                            var answer = confirm("Are you sure you want to close this vote?");
                            if (answer) {
                                window.location = "index.php?close_post=<?php echo $row['id']; ?>";
                            }
                            else {
                            }
                        });
                        $(".delete_comment").click(function() {
                            var answer = confirm("<?php echo_trad('Are you sure you want to delete this comment?'); ?>");
                            if (answer) {
                                window.location = "index.php?delete_comment=" + $(this).attr("id").split("_")[2];
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
