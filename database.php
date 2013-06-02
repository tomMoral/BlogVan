<?php

class Database {

    public static function connect() {
        $bddname = "blog";
        $bddmdp = "";
        $bdduser = 'root';
        $dsn = 'mysql:dbname=' . $bddname . ';host=127.0.0.1';
        
        $dbh = null;
        try {
            $dbh = new PDO($dsn, $bdduser, $bddmdp, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
            exit(0);
        }
        return $dbh;
    }

}

?>