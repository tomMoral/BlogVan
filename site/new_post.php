<?php
include("headerPHP.php");
$user = user::getSessionUser();
if ($user != null && $user->type == 2) {
    if (!isset($_POST['post'])) {
        htmlHeader("blog");
        ?>
        <table>
            <tr>
                <td>
                    <div id="post">
                        <form action="new_post.php" method="post" enctype="multipart/form-data" id="np">
                            <input type="hidden" name="visualization" id="visualization" value=0/>
                            Titre: <input type="text" name="title" id="title"></br>
                            Post: <textarea type="text" id="postarea" name="post"  class="new_post" placeholder='New Post, insert photo at pi'></textarea><br>
                            ([prop1:prop2:...:propn] for a vote)<br>
                            (photo p1...p9)<br>
                            Permission: <input type="checkbox" name="permission" value=1 checked="checked">All<br>
                            <input type="submit"></br>
                            pics :</br><input type="file" name="pic1" id="pic1"></br>
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
                            <p id="bodyvisualization">
                            <div></div>
                            </p>
                        </article>

                    </section>
                </td>
            </tr>
        </table>

        <script>
            $(document).ready(function() {
                $('textarea').autosize();
                $("#postarea").keyup(function() {
                    var text = document.getElementById("postarea").value;
                    $("#bodyvisualization").html(text);
                });
                $("#title").keyup(function() {
                    var text = document.getElementById("title").value;
                    $("#titlevisualization").html(text);
                });
                $("#pic1").change(function() {
                    $("#visualization").attr("value", 1);
                    $("#np").submit();
                });
                $("#np").submit(function() {
                });
            });

        </script>
        <?php
    } else {
        if ($_POST['visualization'] == 0) {
            $i = 1;
            $pics = '';
            $dossier = 'pics_up/';
            while (isset($_FILES["pic$i"])) {
                $fichier = basename($_FILES["pic$i"]['name']);
                if (move_uploaded_file($_FILES["pic$i"]['tmp_name'], $dossier . $fichier)) { //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                    $pics .=',' . Photos::add_photo('', $dossier . $fichier);
                } else { //Sinon (la fonction renvoie FALSE).
                    echo 'echec de l\'upload';
                }
                $i += 1;
            }
            $perm = (isset($_POST['permission'])) ? 1 : 0;
            $count = preg_match_all('/\[([^:]+:)+[^\]]+\]/', $_POST['post']);
            if ($count == 0) {
                $comments = '';
            } else {
                $comments = 'v';
            }
            echo $pics;
            $return = Posts::add_post('GPS1', $_POST['title'], $_POST['post'], $pics, $comments, $perm);
            if ($return == '') {
                header("Location: index.php");
            } else {
                echo $return;
            }
            exit;
        } else {
            //upload the image
            $i = 1;
            $dossier = 'pics_up/';
            while (isset($_FILES["pic$i"])) {
                $fichier = basename($_FILES["pic$i"]['name']);
                $upload_succeed = move_uploaded_file($_FILES["pic$i"]['tmp_name'], $dossier . $fichier);
                if ($upload_succeed) { //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                } else { //Sinon (la fonction renvoie FALSE).
                    echo 'echec de l\'upload';
                }
                $i += 1;
            }
            htmlHeader("blog");
            ?>
            <table>
                <tr>
                    <td>
                        <div id="post">
                            <form action="new_post.php" method="post" enctype="multipart/form-data" id="np">
                                <input type="hidden" name="visualization" id="visualization" value=0/>
                                Titre: <input type="text" name="title" id="title" value="<?php echo $_POST['title']; ?>"></br>
                                Post: <textarea type="text" id="postarea" class="new_post" name="post" placeholder='New Post, insert photo at pi'><?php echo $_POST['post']; ?></textarea><br>
                                ([prop1:prop2:...:propn] for a vote)<br>
                                (photo [p1]...[p9])<br>
                                Permission: <input type="checkbox" name="permission" value=<?php echo $perm = (isset($_POST['permission'])) ? 1 : 0; ?> checked="checked">All<br>
                                <input type="submit"></br>
                                pics :</br><input type="file" name="pic1" id="pic1"></br>
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
                                <p id="bodyvisualization">
                                <div></div>
                                </p>
                            </article>

                        </section>
                    </td>
                </tr>
            </table>

            <script>


                $(document).ready(function() {
                    var pic = [<?php
            $i = 1;
            while (isset($_POST["p$i"])) {
                echo $i == 1 ? "" : ",";
                echo "{code: \"p$i\", name:\"" . $_POST["p$i"] . "\"}";
                $i++;
            }
            if ($upload_succeed) {
                echo $i == 1 ? "" : ",";
                echo "{code: \"p$i\", name:\"" . $dossier . $fichier . "\"}";
            }
            ?>];
                    for (var i = 0; i < pic.length; i++) {
                        $("form").append("</br>" + pic[i].code + " : " + pic[i].name + "  upload succeeded!");
                    }
                    var text = document.getElementById("postarea").value;
                    for (var i = 0; i < pic.length; i++) {
                        var re = new RegExp(pic[i].code, 'g');
                        text = text.replace(re, '<img src="' + pic[i].name + '" style="max-width: 530px"/>');
                    }
                    $("#bodyvisualization").html(text);
                    $("textarea").html(text);
                    var text = document.getElementById("title").value;
                    $("#titlevisualization").html(text);

                    $("#postarea").keyup(function() {
                        var text = document.getElementById("postarea").value;
                        for (var i = 0; i < pic.length; i++) {
                            var re = new RegExp(pic[i].code, 'g');
                            text = text.replace(re, '<img src="' + pic[i].name + '" style="max-width: 530px"/>');
                        }
                        $("#bodyvisualization").html(text);
                        $("textarea").html(text);
                    });
                    $("#title").keyup(function() {
                        var text = document.getElementById("title").value;
                        $("#titlevisualization").html(text);
                    });
                    $("#pic1").change(function() {
                        $("#visualization").attr("value", 1);
                        for (var i = 0; i < pic.length; i++) {
                            $("form").append("<input type=\"hidden\" name=\"" + pic[i].code + "\" value =\"" + pic[i].name + "\"/>");
                        }
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
