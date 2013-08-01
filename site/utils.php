<?php

function welcome_message($last_connexion, $show_smoke = false) {
    echo "<div class='welcome'><div id='div1'>";
    $user = user::getSessionUser();
    $last_posts = 0;
    $dbh = Database::connect();
    $query = "SELECT * FROM `posts` WHERE `time` > \"$last_connexion\"";
    $sth = $dbh->prepare($query);
    $sth->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'user');
    $sth->execute();
    while ($post = $sth->fetch()) {
        $last_posts++;
    }
    $sth->closeCursor();
    $dbh = null;

    if ($show_smoke) {
        echo string_trad("Don't worry ") . $user->name . string_trad(", it happends quite often ;)<br/> Welcome back!");
    } else {
        echo string_trad("Welcome back ") . $user->name . "!";
    }
    if ($last_posts == 1) {
        echo "<br/> $last_posts" . string_trad(" post has been written since your last visit:)");
    } else if ($last_posts) {
        echo "<br/> $last_posts" . string_trad(" posts have been written since your last visit:)");
    }
    echo "</div><div id='div2'><img src='images/6.png'/></div></div>";
}

function good_bye_message($last_user) {
    echo "<div class='welcome'><div id='div1'>";
    echo string_trad("See you soon ") . $last_user . "!";
    echo "</div><div id='div2'><img src='images/6.png'/></div></div>";
    $_SESSION['last_user'] = null;
}

function dateToDuree($date) {
    //renvoie un truc du genre 'il y a 5 jours' à partir d'une datte au format 2013-03-01 00:11:56
    $a = strptime($date, "%Y-%m-%d %H:%M:%S");

    $timestamp = mktime($a['tm_hour'], $a['tm_min'], $a['tm_sec'], $a['tm_mon'] + 1, $a['tm_mday'], $a['tm_year'] + 1900);
    $diff = time() - $timestamp;
    $res = "";
    if ($diff < 60) {
        $res = string_trad("less than one minute");
    } elseif ($diff < 3600) {
        $res = (($diff / 60) % 60) == 1 ? (($diff / 60) % 60) . " minute" : (($diff / 60) % 60) . " minutes";
    } elseif ($diff < 86400) {
        $res = (($diff / 3600) % 24) == 1 ? (($diff / 3600) % 24) . " " . string_trad("hour") : (($diff / 3600) % 24) . " " . string_trad("hour") . "s";
    } elseif ($diff < 604800) {
        $res = (($diff / 86400) % 7) == 1 ? (($diff / 86400) % 7) . " " . string_trad("day") : (($diff / 86400) % 7) . " " . string_trad("day") . "s";
    } elseif ($diff < 2678400) {
        $res = (($diff / 604800) % 5) == 1 ? (($diff / 604800) % 5) . " " . string_trad("week") : (($diff / 604800) % 5) . " " . string_trad("week") . "s";
    } elseif ($diff < 32140800) {
        $res = (($diff / 2678400) % 12) == 1 ? (($diff / 2678400) % 12) . " " . string_trad("month") : (($diff / 2678400) % 12) . " " . string_trad("month") . "s";
    } else {
        $res = string_trad("a long time");
    }
    $user = user::getSessionUser();
    if ($user == null) {
        $language = isset($_SESSION['language']) ? $_SESSION['language'] == "FR" ? "FR" : "EN"  : "EN";
    } else {
        $language = $user->language == "FR" ? "FR" : "EN";
    }
    return $language == "FR" ? "il y a " . $res : $res . " ago";
}

function htmlHeader($tohide) {
    //deso c'est un peu sale...
    echo '

    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style/style.css" />
        <title>Maxi blog du Van VW :)</title>
        <link rel="icon" 
              type="image/png" 
              href="images/logo.png">
        <script type="text/javascript" src="script/jQuery.js"></script>
        <script type="text/javascript" src="script/downloadScripts.js"></script>
        <script type="text/javascript" src="script/script.js"></script>
    </head>

    <body><script>
        $(document).ready(function(){
            $.post("ajax/get_language.php");
            });
        </script>
        <div id="bloc_page"><header> 
<div id="banniere_image">';
    if ($tohide != "connexion") {
        echo '<div id="banniere_description">
        <nav>';
        if ($tohide != "blog") {
            echo '<a href="index.php" class="blog"><div><img src="images/face_purple.png" alt="Logo VW" class="logo" />Blog</div></a>';
        }
        if ($tohide != "photo") {
            echo '               <a href="photos.php" class="photo"><div><img src="images/face.png" alt="Logo VW" class="logo" />Photos</div></a>';
        }
        if ($tohide != "travel") {
            echo '       
                    <a href="travel.php" class="travel"><div><img src="images/face_blue2.png" alt="Logo VW" class="logo" />' . string_trad('Travel') . '</div></a>';
        }
        if (false) {
            echo '       
                    <a href="#" class="team"><div><img src="images/face_red.png" alt="Logo VW" class="logo" />Team</div></a>';
        }
        if ($tohide != "van") {
            echo '       
                    <a href="van.php" class="van"><div><img src="images/face_green.png" alt="Logo VW" class="logo" />Van</div></a>';
        }$user = User::getSessionUser();
        if ($user != null && $user->type == "2") {
            echo '<a href="new_post.php" class="newpost"><div><div class="logo_container"><img src="images/face_red.png" class="logo"/></div><div  class="text_container">' . string_trad('New post') . '</div></div></a>';
        }
        if ($tohide != "connexion") {
            if (!isset($_SESSION['user'])) {
                echo '<a href="connexion.php" class="connexion"><div><div class="image_logo" ><img src="images/face_yellow.png" alt="Logo VW" class="logo" /></div><div class="text_logo">' . string_trad('Get in') . '</div></div></a>';
            } else {
                echo '<a href="deconnexion.php" class="connexion"><div><img src="images/face_yellow.png" alt="Logo VW" class="logo" />' . string_trad('Get out') . '</div></a>';
            }
        }

        echo '
                    </nav>
        
    </div>';
    } echo '</div>
                
            </header>';
    if ($tohide != "connexion") {
        echo '<div id="manage_music">';
        include('moving_guy.php');
        echo '<img src="';
        echo (isset($_SESSION['currentTime']) && isset($_SESSION['playing']) && isset($_SESSION['song_num']) && isset($_SESSION['is_playing']) && $_SESSION['is_playing'] == 1) ? 'images/pause.png"' : 'images/play.png"';
        echo 'width="100px"/></div>
<div id="for_music"></div>
<script>
    $(document).ready(function() {
        $.post("ajax/load_music.php", {last_id: -1})
                .done(function(data) {
            $("#for_music").append(data);
        });
    });
    </script>';
    }
}

function echo_trad($string) {
    echo string_trad($string);
}

function string_trad($string) {
    $user = user::getSessionUser();
    if ($user == null) {
        $language = isset($_SESSION['language']) ? $_SESSION['language'] == "FR" ? "FR" : "EN"  : "EN";
    } else {
        $language = $user->language == "FR" ? "FR" : "EN";
    }
    if ($language == "FR") {
        global $trad;
        if (!isset($trad[$string])) {
            return "***" . $string . "***";
        } else {
            return $trad[$string];
        }
    } else {
        return $string;
    }
}

function guitar_guy(){

}

$trad = array(
    "welcome" => "bienvenu",
    "Don't worry " => "Pas d'inquiétude ",
    ", it happends quite often ;)<br/> Welcome back!" => ", ça arrive souvent ;) <br/> Content de te revoir !",
    "Welcome back " => "Content de te revoir ",
    " post has been written since your last visit:)" => " post a été écrit depuis ta dernière visite:)",
    " posts have been written since your last visit:)" => " posts ont été écrits depuis ta dernière visite:)",
    "See you soon " => "A bientôt ",
    "less than one minute" => "moins d'une minute",
    "hour" => "heure",
    "week" => "semaine",
    "day" => "jour",
    "month" => "mois",
    "a long time" => "longtemps",
    "Travel" => "Voyage",
    "Get in" => "Monte à bord",
    "Get out" => "Dépose moi",
    "Someone is already using this " => "Quelqu\'un utilise déja ce ",
    "try an other one" => "essaye en un autre",
    "This" => "Ce",
    "is available" => "est disponible",
    "You don\'t seem to be registered yet, please provide an email adress and chose your language" => "Tu n\'est pas enregistré? Donnes nous un email et choisis ta langue!",
    "You don\'t seem to be registered yet, please provide a user name and chose your language" => "Tu n\'est pas enregistré? Choisis un chouette nom d\'utilisateur et ta langue",
    "If this is your first time, try another username. This on is already taken :(<br/>Else keep trying!" => "Si c'est la première fois que tu viens, essaye un autre nom d'utilisateur. Celui-ci est déjà pris :(<br/><br/>Sinon essaye encore!",
    "try again" => "essaye encore",
    "Let's go" => "C'est parti",
    "Welcome on board" => "Bienvenu a bord",
    "Write something" => "Commenter",
    "Get in to be able to comment" => "Connecte toi pour pouvoir commenter",
    "The adventure begins in" => "L'aventure commence dans",
    "Password" => "Mot de passe",
    "Username" => "Nom d'utilisateur",
    "less than one minute ago" => "il y a moins d'une minute",
    "delete" => "supprimer",
    "close" => "clore",
    "show all comments" => "afficher tout les coms",
    "hide comments" => "masquer les coms",
    "modify" => "modifier",
    "Are you sure you want to delete this comment?" => "Etes vous bien sur de vouloir effacer ce chouette commentaire ?",
    "this is really cool!" => "ça c'est cool!",
    "you find it really cool!" => "tu trouves ça très cool!",
    "New post" => "Nouveau post",
    "On the agenda"=>"Au programme",
    "2500 miles of aventure, sweat and laugther"=>"2500 miles d'aventure de sueur et de rire ",
    "lot of beers to fight Death Valley heat"=>"beaucoup de bières contre la chaleur de la Death Valley",
    "wedding at Vegas"=>" mariage à Vegas",
    "earplugs to let Thomas sing with the radio"=>"boules Quies pour laisser Thomas chanter avec la radio",
    "divorce at Vegas"=>"divorce à Vegas",
    "a cooler to put Micheaux in when she is too hot and the beers are gone"=>"une glacière pour mettre Micheaux dedans quand elle aura trop chaud et qu'on aura bu toutes les bieres",
    "a tent to prevent Marine from taking all the space in the van"=>" une tente pour éviter à Marine de prendre toute la place dans le van",
    "bankruptcy at Vegas"=>"banqueroute à Vegas",
    "no grimace on Greg's photos"=>" pas de grimace sur les photos de Greg",
    "culturation in museums or lying on the beach"=>"culturation dans des musées ou allongé sur la plage au soleil",
    "car breakdown"=>"panne régulière",
    "waking up Guigui while driving"=>"réveil de Guigui au volant",
    "a lot of other surprises and above all"=>"plein d'autre surprises et surtout",
    "a lot of posts and photos !"=>"beaucoup de posts et de photos !",
    "It's awesome!"=>"C'est génial !",
    "The adventure has begun since"=>"L'aventure a commencé il y a"
        
    );
?>
