<?php


class user {

    public $id;
    public $name;
    public $email;
    public $password;
    public $first_connexion;
    public $last_connexion;
    public $type;

    function __construct() {
        $this->id = -1;
        $this->name = "";
        $this->email = "";
        $this->password = "";
        $this->first_connexion = -1;
        $this->last_connexion = -1;
        $this->type = -1;
    }

    static function create($name, $password, $email = NULL) {

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


        $user = user::getByName($name);
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

    public static function getByName($name) {
        $dbh = Database::connect();
        $query = "SELECT * FROM `user` WHERE `name` = \"$name\"";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'user');
        $sth->execute();
        $user = $sth->fetch();
        $sth->closeCursor();
        $dbh = null;
        return $user;
    }
    
    public static function getByEmail($email) {
        $dbh = Database::connect();
        $query = "SELECT * FROM `user` WHERE `email` = \"$email\"";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'user');
        $sth->execute();
        $user = $sth->fetch();
        $sth->closeCursor();
        $dbh = null;
        return $user;
    }
    
    public static function getSessionUser() {
        $dbh = Database::connect();
        $id=$_SESSION['user'];
        $query = "SELECT * FROM `user` WHERE `id` = \"$id\"";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'user');
        $sth->execute();
        $user = $sth->fetch();
        $sth->closeCursor();
        $dbh = null;
        return $user;
    }

    public function loginByName($name, $password) {
        $user = user::getByName($name);
        echo $user->name.'</br>';
        echo $user->password.'</br>';
        echo $password;
        if ($password == $user->password) {
            $_SESSION['user'] = $user->id;
            $this->set_last_connexion();
            return true;
        } else {
            return false;
        }
    }

    public function loginByEmail($email, $password) {
        $user = user::getByEmail($email);
        if ($password == $user->password) {
            $_SESSION['user'] = $user->id;
            $this->set_last_connexion();
            return true;
        } else {
            return false;
        }
    }
    
    public function logOut(){
        $_SESSION['user']=null;
        header('Location: index.php?deconnexion=true');
    }

    public function set_last_connexion() {
        $dbh = Database::connect();
        $query = "UPDATE user SET last_connexion = NOW() WHERE id = '$this->id'";
        $sth = $dbh->prepare($query);
        //$sth->setFetchMode(PDO::FETCH_CLASS, 'Utilisateurs');
        $sth->execute();
        $dbh = null;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getFirstConnexion() {
        return $this->first_connexion;
    }

    public function getLastConnexion() {
        return $this->last_connexion;
    }

    public function getType() {
        return $this->type;
    }

    public function getEmail() {
        return $this->email;
    }

}

?>
