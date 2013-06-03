<?php

include_once("database.php");

class user {

    private $id;
    private $name;
    private $email;
    private $password;
    private $first_connexion;
    private $last_connexion;
    private $type;

    function __construct() {
        $this->id = -1;
        $this->name = "";
        $this->email = "";
        $this->password = "";
        $this->first_connexion = -1;
        $this->last_connexion = -1;
        $this->type = -1;
    }

    function create($name, $password, $email = NULL) {

        $dbh = Database::connect();
        $sth = $dbh->prepare('CREATE TABLE IF NOT EXISTS `user` (
                            `id` int(11) NOT NULL,
                            `name` varchar(255) NOT NULL,
                            `email` varchar(255) DEFAULT NULL,
                            `first_connexion` date NOT NULL,
                            `last_connexion` date NOT NULL,
                            `password` varchar(255) NOT NULL,
                            `type` int(11) NOT NULL,
                            PRIMARY KEY (`id`)
                          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
        $sth->execute();


        $user = user::get($name);
        if ($user == null) {
            $id = user::nextId();
            $sth = $dbh->prepare("INSERT INTO `user` (`id`, `name`, `email`, `first_connexion`,  `last_connexion`,  `password`, `type`) VALUES(?,?,?,NOW(),NOW(),?,?)");
            $sth->execute(array($id, $name, $email, $password, 0));
            $dbh = null;
        }
    }

    static function nextId() {
        $query = "SELECT `id` FROM `user` ORDER BY id DESC;";
        $dbh = Database::connect();
        $sth = $dbh->prepare($query);
        $sth->execute();
        $courant = $sth->fetch(PDO::FETCH_ASSOC);
        $id = $courant['id'] + 1;
        return $id;
    }

    public static function get($name) {
        $dbh = Database::connect();
        $query = "SELECT * FROM `user` WHERE `name` = \"$name\"";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'user');
        $request_succeeded = $sth->execute();
        $user = null;
        if ($request_succeeded) {
            $user = $sth->fetch();
        }
        $sth->closeCursor();
        $dbh = null;
        return $user;
    }
}

?>