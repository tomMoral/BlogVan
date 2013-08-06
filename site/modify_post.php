<?php
include("headerPHP.php");
include_once("class/resize.php");
$user = user::getSessionUser();
if ($user != null && $user->type == 2 && (isset($_GET['id_modify']) && Posts::get_by_id($_GET['id_modify'])) || isset($_POST['post'])) {
    if (!isset($_POST['post'])) {
        ?>
        <script>
            function vizualizePostEN() {
                var text = $("#postarea").val().replace(/\r?\n/g, '<br/>');
                $("#bodyvisualization").html(text);
            }
            function vizualizePostFR() {
                var text = $("#postareaFrench").val().replace(/\r?\n/g, '<br/>');
                $("#bodyvisualizationFrench").html(text);
            }
            function vizualizeTitleEN() {
                var text = document.getElementById("title").value;
                $("#titlevisualization").html(text);
            }
            function vizualizeTitleFR() {
                var text = document.getElementById("titleFrench").value;
                $("#titlevisualizationFrench").html(text);
            }</script>
        <?php
        $modify_post = Posts::get_by_id($_GET['id_modify']);
        htmlHeader("photo");
        ?>
        Write a post in French and English. You can create a pool with [proposition1::...::last proposition] and insert images with @1, @2... (don't forget to add the image) What you see is what you get. Enjoy!
        <table class="tablepost">
            <tr>
                <td >


                    <div id="post" style="position:absolute; top:0px; width:270px;">
                        <form action="modify_post.php" method="post" enctype="multipart/form-data" id="np">
                            <input type="hidden" name="visualization" id="visualization" value=0 />
                            <input type="hidden" name="lastUsed" id="lastUsed" value=1 />
                            <input type="hidden" name="id" value=<?php echo $modify_post->id; ?> />
                            English<br/>
                            <input type="text" name="title" id="title" placeholder="Title" required="required" <?php echo " value='$modify_post->title'"; ?>><br/><br/>
                            <textarea type="text" id="postarea" name="post"  class="new_post" placeholder='New Post, insert photo at @i, vote at [prop1::prop2::...::propn]' required="required"><?php echo "$modify_post->body"; ?></textarea><br/><br>
                            French<br/>
                            <input type="text" name="titleFrench" id="titleFrench" placeholder="Titre" required="required" <?php echo " value='$modify_post->title_french'"; ?>><br/><br/>
                            <textarea type="text" id="postareaFrench" name="postFrench"  class="new_post" placeholder='Nouveau post, inserer les photos à @i, les votes à [prop1::prop2::...::propn]' required="required"><?php echo "$modify_post->body_french"; ?></textarea><br/><br>

                            Permission: <input type="checkbox" name="permission" value=<?php echo ($modify_post->permission != 1) ? "0" : "1 checked=\"checked\""; ?>>All<img src="images/refresh.png" id="refresh" style="width:25px; position: absolute; left:230px;"/><br/>
                            <br/>Attention, set the permission before choosing the photos<br/><br/>


                            <input type="submit"><br/><br/>
                            pics :<br/><div id="image"><input type="file" name="pic1" id="pic1"></div><br/>
                        </form>
                    </div>
                </td>
                <td>
                    <section class="post">
                        <article>
                            <div class="title">
                                <h1>
                                    <div id="titlevisualization"></div>

                                </h1>
                                <legend>
                                    less than one minute ago
                                </legend>
                            </div>
                            <div class="body_post">
                                <p id="bodyvisualization">
                                <div></div></div>
                            </p>
                        </article>

                    </section>
                    <section class="post">
                        <article>
                            <div class="title">
                                <h1>
                                    <div id="titlevisualizationFrench"></div>

                                </h1>
                                <legend>
                                    <?php echo_trad("less than one minute ago"); ?>
                                </legend>
                            </div>
                            <div class="body_post">
                                <div id="bodyvisualizationFrench">
                                    <div></div></div>
                            </div>
                        </article>

                    </section>
                </td>
            </tr>
        </table>

        <script>
            var lastFieldUsed = 1;
            var pic = [];
            $(document).ready(function() {
                vizualizePostEN();
                vizualizePostFR();
                vizualizeTitleEN();
                vizualizeTitleFR();
                $('textarea').autosize();
                $("#postarea").keyup(function() {
                    lastFieldUsed = 1;
                    vizualizePostEN();
                });
                $("#title").keyup(function() {
                    vizualizeTitleEN();
                });
                $("#postareaFrench").keyup(function() {
                    lastFieldUsed = 2;
                    vizualizePostFR();
                });
                $("#titleFrench").keyup(function() {
                    vizualizeTitleFR();
                });
                $("#pic1").change(function() {
                    $("#lastUsed").attr("value", lastFieldUsed);
                    $("#visualization").attr("value", 1);
                    $("#np").submit();
                });
                $("#np").submit(function() {
                });
                $("#refresh").click(function() {
                    $("#lastUsed").attr("value", lastFieldUsed);
                    $("#visualization").attr("value", 2);
                    $("#np").submit();
                });
            });

        </script>
        <?php
    } else {
        if ($_POST['visualization'] == 0) {
            $i = 1;
            $pics = '';
            $perm = (isset($_POST['permission'])) ? 1 : 0;
            $dossier = $perm == 1 ? 'pics_up/A/' : 'pics_up/B/';
            while (isset($_FILES["pic$i"]) && isset($_FILES["pic$i"]['name']) && $_FILES["pic$i"]['name'] != "") {
                $fichier = date("m-d-H-i-s") . basename($_FILES["pic$i"]['name']);

                $image = new SimpleImage();
                $image->load($_FILES["pic$i"]['tmp_name']);
                $image->resizeToWidth(530);
                $image->save($dossier . $fichier);
                $i += 1;
            }
            $perm = (isset($_POST['permission'])) ? 1 : 0;
            $return = Posts::modify_post($_POST['id'], 'GPS1', $_POST['title'], $_POST['titleFrench'], str_replace("#####", $_POST['id'], nl2br($_POST['post'])), str_replace("#####", $_POST['id'], nl2br($_POST['postFrench'])), $perm);
            if ($return == '') {
                header("Location: index.php");
            } else {
                print_r($return);
            }
            exit;
        } else {
            //upload the image
            $i = 1;
            $perm = (isset($_POST['permission'])) ? 1 : 0;
            $dossier = $perm == 1 ? 'pics_up/A/' : 'pics_up/B/';
            while (isset($_FILES["pic$i"]) && isset($_FILES["pic$i"]['name']) && $_FILES["pic$i"]['name'] != "") {
                $fichier = date("m-d-H-i-s") . basename($_FILES["pic$i"]['name']);

                $image = new SimpleImage();
                $image->load($_FILES["pic$i"]['tmp_name']);
                $image->resizeToWidth(530);
                $image->save($dossier . $fichier);
                $i += 1;
            }
            htmlHeader("blog");
            ?> <script>
                            function vizualizePostEN() {
                                var text = $("#postarea").val().replace(/\r?\n/g, '<br/>');
                                $("#bodyvisualization").html(text);
                            }
                            function vizualizePostFR() {
                                var text = $("#postareaFrench").val().replace(/\r?\n/g, '<br/>');
                                $("#bodyvisualizationFrench").html(text);
                            }
                            function vizualizeTitleEN() {
                                var text = document.getElementById("title").value;
                                $("#titlevisualization").html(text);
                            }
                            function vizualizeTitleFR() {
                                var text = document.getElementById("titleFrench").value;
                                $("#titlevisualizationFrench").html(text);
                            }</script>
            Write a post in French and English. You can create a pool with [proposition1::...::last proposition] and insert images with @1, @2... (don't forget to add the image) What you see is what you get. Enjoy!


            <table class="tablepost">
                <tr>
                    <td >
                        <div id="post" style="position:absolute; top:0px; width:270px;">
                            <form action="modify_post.php" method="post" enctype="multipart/form-data" id="np">
                                <input type="hidden" name="visualization" id="visualization" value=0 />
                                <input type="hidden" name="lastUsed" id="lastUsed" value=1 />
                                <input type="hidden" name="id" value=<?php echo $_POST['id']; ?> />
                                English<br/>
                                <input type="text" name="title" id="title" value="<?php echo $_POST['title']; ?>" placeholder="Title" required="required"><br/><br/>
                                <textarea type="text" id="postarea" class="new_post" name="post" placeholder='New Post, insert photo at @i, vote at [prop1::prop2::...::propn]' required="required"><?php echo $_POST['post']; ?></textarea><br/><br>
                                French<br/>
                                <input type="text" name="titleFrench" id="titleFrench" value="<?php echo $_POST['titleFrench']; ?>" placeholder="Title" required="required"><br/><br/>
                                <textarea type="text" id="postareaFrench" class="new_post" name="postFrench" placeholder='New Post, insert photo at @i, vote at [prop1::prop2::...::propn]' required="required"><?php echo $_POST['postFrench']; ?></textarea><br/><br>
                                Permission: <input type="checkbox" name="permission" value=<?php echo $perm = (isset($_POST['permission'])) ? 1 : 0; ?> <?php echo $perm = (isset($_POST['permission'])) ? "checked=\"checked\"" : ""; ?>>All  <img src="images/refresh.png" id="refresh" style="width:25px; position: absolute; left:230px;"/><br>
                                <br/>Attention, set the permission before choosing the photos
                                <br/><br/>
                                <input type="submit"><br/><br/>
                                pics :<br/><div id="image"><input type="file" name="pic1" id="pic1"></div><br/>
                            </form>
                        </div>
                    </td>
                    <td>
                        <section class="post">
                            <article>
                                <div class="title">
                                    <h1>
                                        <div id="titlevisualization"></div>

                                    </h1>
                                    <legend>
                                        less than one minute ago
                                    </legend>
                                </div>
                                <div class="body_post">
                                    <p id="bodyvisualization">
                                </div><div></div>
                                </p>
                            </article>

                        </section>
                        <section class="post">
                            <article>
                                <div class="title">
                                    <h1>
                                        <div id="titlevisualizationFrench"></div>

                                    </h1>
                                    <legend>
                                        <?php echo_trad("less than one minute ago"); ?>
                                    </legend>
                                </div>
                                <div class="body_post">
                                    <div id="bodyvisualizationFrench">
                                    </div><div></div>
                                </div>
                            </article>

                        </section>
                    </td>
                </tr>
            </table>

            <script>
                var lastFieldUsed = <?php echo $_POST['lastUsed']; ?>;
                $(document).ready(function() {
                    //write the pctures used
                    var pic = [<?php
                            $i = 1;
                            while (isset($_POST["@$i"])) {
                                echo $i == 1 ? "" : ",";
                                echo "{code: \"@$i\", name:\"" . $_POST["@$i"] . "\"}";
                                $i++;
                            }
                            if (isset($fichier)) {
                                echo $i == 1 ? "" : ",";
                                echo "{code: \"@$i\", name:\"" . $dossier . $fichier . "\"}";
                            }
                            ?>];
                    for (var i = 0; i < pic.length; i++) {
                        $("form").append("<br/>" + pic[i].code + " : " + pic[i].name + " <br/> upload succeeded!");
                    }
                    //reaplce the pictures in the code
                    var text = document.getElementById("postarea").value;
                    for (var i = 0; i < pic.length; i++) {
                        var re = new RegExp(pic[i].code, 'g');
                        text = text.replace(re, '<img src="' + pic[i].name + '" style="max-width: 530px"/>');
                    }
                    $("#postarea").html(text);

                    var text = document.getElementById("postareaFrench").value;
                    for (var i = 0; i < pic.length; i++) {
                        var re = new RegExp(pic[i].code, 'g');
                        text = text.replace(re, '<img src="' + pic[i].name + '" style="max-width: 530px"/>');
                    }
                    $("#postareaFrench").html(text);

                    vizualizePostEN();
                    vizualizePostFR();
                    vizualizeTitleEN();
                    vizualizeTitleFR();

                    $('textarea').autosize();
                    $("#postarea").keyup(function() {
                        lastFieldUsed = 1;
                        var text = $("#postarea").val().replace(/\r?\n/g, '<br/>');
                        for (var i = 0; i < pic.length; i++) {
                            if (text.indexOf(pic[i].code) !== -1) {
                                $("#visualization").attr("value", 1);
                                for (var i = 0; i < pic.length; i++) {
                                    $("form").append("<input type=\"hidden\" name=\"" + pic[i].code + "\" value =\"" + pic[i].name + "\"/>");
                                }
                                $("#pic1").remove();
                                $("#lastUsed").attr("value", lastFieldUsed);
                                $("#np").submit();
                            }
                            var re = new RegExp(pic[i].code, 'g');
                            text = text.replace(re, '<img src="' + pic[i].name + '" style="max-width: 530px"/>');
                        }
                        $("#bodyvisualization").html(text);
                        $("#postarea").html(text);
                    });
                    $("#title").keyup(function() {
                        var text = document.getElementById("title").value;
                        $("#titlevisualization").html(text);

                        document.getElementById('postarea').value = text;

                        document.getElementById('postarea').value = text;

                    });

                    $("#postareaFrench").keyup(function() {
                        lastFieldUsed = 2;
                        var text = $("#postareaFrench").val().replace(/\r?\n/g, '<br/>');
                        for (var i = 0; i < pic.length; i++) {
                            if (text.indexOf(pic[i].code) !== -1) {
                                $("#visualization").attr("value", 2);
                                for (var i = 0; i < pic.length; i++) {
                                    $("form").append("<input type=\"hidden\" name=\"" + pic[i].code + "\" value =\"" + pic[i].name + "\"/>");
                                }
                                $("#pic1").remove();
                                $("#lastUsed").attr("value", lastFieldUsed);
                                $("#np").submit();
                            }
                            var re = new RegExp(pic[i].code, 'g');
                            text = text.replace(re, '<img src="' + pic[i].name + '" style="max-width: 530px"/>');
                        }
                        $("#bodyvisualizationFrench").html(text);
                        $("#postareaFrench").html(text);
                    });
                    $("#titleFrench").keyup(function() {
                        var text = document.getElementById("titleFrench").value;
                        $("#titlevisualizationFrench").html(text);
                    });

                    $("#pic1").change(function() {
                        $("#visualization").attr("value", 1);
                        for (var i = 0; i < pic.length; i++) {
                            $("form").append("<input type=\"hidden\" name=\"" + pic[i].code + "\" value =\"" + pic[i].name + "\"/>");
                        }
                        $("#lastUsed").attr("value", lastFieldUsed);
                        $("#np").submit();
                    });
                    $("#refresh").click(function() {
                        $("#visualization").attr("value", 1);
                        for (var i = 0; i < pic.length; i++) {
                            $("form").append("<input type=\"hidden\" name=\"" + pic[i].code + "\" value =\"" + pic[i].name + "\"/>");
                        }
                        $("#pic1").remove();
                        $("#lastUsed").attr("value", lastFieldUsed);
                        $("#np").submit();
                    });


            <?php echo $_POST['lastUsed'] == 1 ? '$("#postarea").focus();' : '$("#postareaFrench").focus();'; ?>


                    $("#refresh").click(function() {
                        $("#visualization").attr("value", 1);

                        for (var i = 0; i < pic.length; i++) {
                            $("form").append("<input type=\"hidden\" name=\"" + pic[i].code + "\" value =\"" + pic[i].name + "\"/>");
                        }
                        $("#pic1").remove();
                        $("#np").submit();
                    });
                });

            </script><?php
        }
    }
} else {
    header('Location: index.php');
}
?>
