<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css" />
        <title>Maxi blog du Van VW :)</title>
        <link rel="icon" 
              type="image/png" 
              href="images/logo.png">
        <script type="text/javascript" src="jQuery.js"></script>
        <script type="text/javascript" src="downloadScripts.js"></script>
        <script type="text/javascript" src="script.js"></script>

        <?php
        include('post.php');
        include('user.php');
        $user = new user();
        $user->create("g", "mdp");
        ?>
    </head>

    <body>
        <div id="bloc_page"><header> 

            <nav>
                <div><a href="#"><img src="images/face.png" alt="Logo VW" id="logo" />Photos</a></div>
                <div><a href="#"><img src="images/face_blue.png" alt="Logo VW" id="logo" />Trajet</a></div>
                <div><a href="#"><img src="images/face_red.png" alt="Logo VW" id="logo" />Team</a></div>
                <div><a href="#"><img src="images/face_green.png" alt="Logo VW" id="logo" />Van</a></div>
                <div><a href="connexion.php"><img src="images/face_yellow.png" alt="Logo VW" id="logo" />Get in</a></div>
            </nav>
            <div id="banniere_image">
                <div id="banniere_description">
                    Blog du VW bus...
                    <a href="#" class="bouton_rouge">
                        Voir l'article 
                        <img src="images/flecheblanchedroite.png" alt="" />
                    </a>
                </div>
            </div>
        </header>