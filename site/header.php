<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style/style.css" />
        <title>Maxi blog du Van VW :)</title>
        <link rel="icon" 
              type="image/png" 
              href="../images/logo.png">
        <script type="text/javascript" src="script/jQuery.js"></script>
        <script type="text/javascript" src="script/downloadScripts.js"></script>
        <script type="text/javascript" src="script/script.js"></script>

        <?php
        include('class/post.php');
        include('class/user.php');
        include('utils.php');
        $user = new user();
        $user->create("t", "mdp");
        ?>
    </head>

    <body>
        <div id="bloc_page"><header> 

            <nav>
                <div><a href="#"><img src="../images/face.png" alt="Logo VW" id="logo" />Photos</a></div>
                <div><a href="#"><img src="../images/face_blue.png" alt="Logo VW" id="logo" />Trajet</a></div>
                <div><a href="#"><img src="../images/face_red.png" alt="Logo VW" id="logo" />Team</a></div>
                <div><a href="#"><img src="../images/face_green.png" alt="Logo VW" id="logo" />Van</a></div>
                <div><a href="connexion.php"><img src="../images/face_yellow.png" alt="Logo VW" id="logo" />Get in</a></div>
            </nav>
        </header>