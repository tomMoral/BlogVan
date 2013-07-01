<?php
include("headerPHP.php");
$user = user::getSessionUser();
if ($user != null && $user->type == 2) {
    if (!isset($_POST['post'])) {
        htmlHeader("blog");
        ?>
        <table class="tablepost">
            <tr>
                <td >
                    <div id="post" style="position:absolute; top:0px; width:270px;">
                        <form action="new_post.php" method="post" enctype="multipart/form-data" id="np">
                            <input type="hidden" name="visualization" id="visualization" value=0/>
                            <input type="text" name="title" id="title" placeholder="Title"></br></br>
                            <textarea type="text" id="postarea" name="post"  class="new_post" placeholder='New Post, insert photo at @i, vote at [prop1::prop2::...::propn]'></textarea></br><br>
                            <input type="text" name="titleFrench" id="titleFrench" placeholder="Titre"></br></br>
                            <textarea type="text" id="postareaFrench" name="postFrench"  class="new_post" placeholder='Nouveau post, inserer les photos à @i, les votes à [prop1::prop2::...::propn]'></textarea></br><br>

                            Permission: <input type="checkbox" name="permission" value=1 checked="checked">All</br>
                            </br>Attention, set the permission before choosing the photos</br></br>
                            <input type="submit"></br></br>
                            pics :</br><div id="image"><input type="file" name="pic1" id="pic1"></div></br>
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
                                    less than one minute ago
                                </legend>
                            </div>
                            <div class="body_post">
                                <p id="bodyvisualizationFrench">
                                <div></div></div>
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
                $("#postareaFrench").keyup(function() {
                    var text = document.getElementById("postareaFrench").value;
                    $("#bodyvisualizationFrench").html(text);
                });
                $("#titleFrench").keyup(function() {
                    var text = document.getElementById("titleFrench").value;
                    $("#titlevisualizationFrench").html(text);
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
            $perm = (isset($_POST['permission'])) ? 1 : 0;
            $dossier = $perm == 1 ? 'pics_up/A/' : 'pics_up/B/';
            while (isset($_FILES["pic$i"])) {
                $fichier = date("m-d-H-i-s") . basename($_FILES["pic$i"]['name']);
                if (move_uploaded_file($_FILES["pic$i"]['tmp_name'], $dossier . $fichier)) { //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                    $pics .=',' . Photos::add_photo('', $dossier . $fichier);
                } else { //Sinon (la fonction renvoie FALSE).
                    echo 'echec de l\'upload';
                }
                $i += 1;
            }
            $perm = (isset($_POST['permission'])) ? 1 : 0;
            echo $pics;
            $return = Posts::add_post('GPS1', $_POST['title'],$_POST['titleFrench'], $_POST['post'],$_POST['postFrench'], $pics, '', $perm);
            if ($return == '') {
                header("Location: index.php");
            } else {
                echo $return;
            }
            exit;
        } else {
            //upload the image
            $i = 1;
            $perm = (isset($_POST['permission'])) ? 1 : 0;
            $dossier = $perm == 1 ? 'pics_up/A/' : 'pics_up/B/';
            while (isset($_FILES["pic$i"])) {
                $fichier = date("m-d-H-i-s") . basename($_FILES["pic$i"]['name']);
                $upload_succeed = move_uploaded_file($_FILES["pic$i"]['tmp_name'], $dossier . $fichier);
                if ($upload_succeed) { //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                } else { //Sinon (la fonction renvoie FALSE).
                    echo 'echec de l\'upload';
                }
                $i += 1;
            }
            htmlHeader("blog");
            ?>
            <table class="tablepost">
                <tr>
                    <td >
                        <div id="post" style="position:absolute; top:0px; width:270px;">
                            <form action="new_post.php" method="post" enctype="multipart/form-data" id="np">
                                <input type="hidden" name="visualization" id="visualization" value=0/>
                                <input type="text" name="title" id="title" value="<?php echo $_POST['title']; ?>" placeholder="Title"></br></br>
                                <textarea type="text" id="postarea" class="new_post" name="post" placeholder='New Post, insert photo at @i, vote at [prop1::prop2::...::propn]'><?php echo $_POST['post']; ?></textarea></br><br>
                                <input type="text" name="titleFrench" id="titleFrench" value="<?php echo $_POST['titleFrench']; ?>" placeholder="Title"></br></br>
                                <textarea type="text" id="postareaFrench" class="new_post" name="postFrench" placeholder='New Post, insert photo at @i, vote at [prop1::prop2::...::propn]'><?php echo $_POST['postFrench']; ?></textarea></br><br>
                                Permission: <input type="checkbox" name="permission" value=<?php echo $perm = (isset($_POST['permission'])) ? 1 : 0; ?> <?php echo $perm = (isset($_POST['permission'])) ? "checked=\"checked\"" : ""; ?>>All  <img src="../images/refresh.png" id="refresh" style="width:25px; position: absolute; left:230px;"/><br>
                                </br>Attention, set the permission before choosing the photos
                                </br></br>
                                <input type="submit"></br></br>
                                pics :</br><input type="file" name="pic1" id="pic1"></br></br>
                            </form><input type="submit" name="refresh" id="refresh" value="refresh"></br>
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
                                        less than one minute ago
                                    </legend>
                                </div>
                                <div class="body_post">
                                    <p id="bodyvisualizationFrench">
                                </div><div></div>
                                </p>
                            </article>

                        </section>
                    </td>
                </tr>
            </table>

            <script>


                $(document).ready(function() {
                    $("textarea").focus();

                    var pic = [<?php
            $i = 1;
            while (isset($_POST["@$i"])) {
                echo $i == 1 ? "" : ",";
                echo "{code: \"@$i\", name:\"" . $_POST["@$i"] . "\"}";
                $i++;
            }
            if (isset($upload_succeed) && $upload_succeed) {
                echo $i == 1 ? "" : ",";
                echo "{code: \"@$i\", name:\"" . $dossier . $fichier . "\"}";
            }
            ?>];
                    for (var i = 0; i < pic.length; i++) {
                        $("form").append("</br>" + pic[i].code + " : " + pic[i].name + " <br/> upload succeeded!");
                    }
                    var text = document.getElementById("postarea").value;
                    for (var i = 0; i < pic.length; i++) {
                        var re = new RegExp(pic[i].code, 'g');
                        text = text.replace(re, '<img src="' + pic[i].name + '" style="max-width: 530px"/>');
                    }
                    $("#bodyvisualization").html(text);
                    $("#postarea").html(text);
                    var text = document.getElementById("title").value;
                    $("#titlevisualization").html(text);
                    
                    var text = document.getElementById("postareaFrench").value;
                    for (var i = 0; i < pic.length; i++) {
                        var re = new RegExp(pic[i].code, 'g');
                        text = text.replace(re, '<img src="' + pic[i].name + '" style="max-width: 530px"/>');
                    }
                    $("#bodyvisualizationFrench").html(text);
                    $("#postareaFrench").html(text);
                    var text = document.getElementById("titleFrench").value;
                    $("#titlevisualizationFrench").html(text);

                    $('textarea').autosize();
                    $("#postarea").keyup(function() {
                        var text = document.getElementById("postarea").value;
                        for (var i = 0; i < pic.length; i++) {
                            if (text.indexOf(pic[i].code) !== -1) {
                                $("#visualization").attr("value", 1);
                                for (var i = 0; i < pic.length; i++) {
                                    $("form").append("<input type=\"hidden\" name=\"" + pic[i].code + "\" value =\"" + pic[i].name + "\"/>");
                                }
                                $("#pic1").remove();
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
                    });
                    
                    $("#postareaFrench").keyup(function() {
                        var text = document.getElementById("postareaFrench").value;
                        for (var i = 0; i < pic.length; i++) {
                            if (text.indexOf(pic[i].code) !== -1) {
                                $("#visualization").attr("value", 1);
                                for (var i = 0; i < pic.length; i++) {
                                    $("form").append("<input type=\"hidden\" name=\"" + pic[i].code + "\" value =\"" + pic[i].name + "\"/>");
                                }
                                $("#pic1").remove();
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
                        $("#np").submit();
                    });
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
