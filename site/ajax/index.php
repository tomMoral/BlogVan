<?php

//do as index, used for the smoke transition
include("../headerPHP.php");
$bad_password = false;
if (isset($_POST['name'])) {
    $_SESSION['type']=isset($_POST['type']) &&  $_POST['type'] =="smoke" ? "smoke" : "usual";
    $name = htmlspecialchars($_POST['name']);
    if (isset($_SESSION['try_connexion'][$name])) {
        $_SESSION['try_connexion'][$name]++;
    } else {
        $_SESSION['try_connexion'] = null;
        $_SESSION['try_connexion'][$name] = 1;
    }
    $password = sha1(htmlspecialchars($_POST['password']));
    $pos = strrpos($name, '@');
    $type = ($pos === false) ? 'name' : 'email';

    if ($type == 'name') {
        $user = user::getByName($name);
        if ($user == null) {
            //then create user
            $email = htmlspecialchars($_POST['email']);
            user::create($name, $password, $email);
            include("../index.php");//?firstconnexion=true
        } else {
            //then identify user
            if ($user->loginByName($name, $password)) {
                $_SESSION['connexion']=true;
                include("../index.php");
               //?connexion=true
            } else {
                $bad_password = true;
            }
        }
    } else {
        //similar to first case
        $user = user::getByEmail($name);
        if ($user == null) {
            //then create user
            $email = htmlspecialchars($_POST['email']);
            user::create($email, $password, $name);
            include("../index.php");//?firstconnexion=true
        } else {
            //then identify user
            if ($user->loginByEmail($name, $password)) {
                $_SESSION['connexion']=true;
                include("../index.php");//?connexion=true
            } else {
                $bad_password = true;
            }
        }
    }
} else {
    echo '<SCRIPT LANGUAGE="JavaScript">
     document.location.href="connexion.php"
</SCRIPT>';
}
?>
