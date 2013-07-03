<?php

class Comments {

    public $id_com;
    public $coms_tab;

    function __construct($list_coms = "") {
        $this->id_coms = $list_coms;
        $this->get_com();
    }

    function get_com() {
        if ($this->id_coms != "") {
            $dbh = Database::connect();
            $newparams = array();
            $coms = $this->id_coms;
            $query = $dbh->prepare("SELECT * FROM `comments` WHERE `id` IN ($coms)");

            $query->execute(array());

            $this->coms_tab = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $this->coms_tab[] = $row;
            }
        } else {
            $this->coms_tab = array();
        }
    }

    static function add_comment($user, $body) {
        $dbh = Database::connect();
        $query = $dbh->prepare('CREATE TABLE IF NOT EXISTS `comments` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
                            `time` datetime NOT NULL,
                            `user` varchar(255) DEFAULT "",
                            `body` text NOT NULL,
                            PRIMARY KEY (`id`)
                            )  ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4'
        );
        $query->execute();

        $query = $dbh->prepare("INSERT INTO  `comments` (
                    `user` , `time` ,  `body` ) 
                    VALUES (?, NOW(), ? )");
        $query->execute(array($user, $body));

        return $dbh->lastInsertId();
    }

    static function get_com_by_id($id) {
        $dbh = Database::connect();
        $query = "SELECT * FROM `comments` WHERE `id` = \"$id\"";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'comments');
        $sth->execute();
        $com = $sth->fetch();
        $sth->closeCursor();
        $dbh = null;
        return $com;
    }

    static function delete($id) {
        $post_db = new Posts();
        $dbh = Database::connect();
        $query = "SELECT * FROM posts WHERE (comments='$id' OR comments LIKE '$id,%' OR comments LIKE '%,$id' OR comments LIKE '%,$id,%')";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'posts');
        $sth->execute();
        $post = $sth->fetch();
        $comments = explode(",", $post->comments);
        $next_comment = "";
        $i = 0;
        foreach ($comments as $com) {
            if ($com != $id) {
                $next_comment .=$i == 0 ? $com : "," . $com;
                $i++;
            }
        }
        $query = "UPDATE posts SET comments = '$next_comment' WHERE id = '$post->id'";
        $sth = $dbh->prepare($query);
        $sth->execute();
        $query = "DELETE FROM comments WHERE id='$id'";
        $sth = $dbh->prepare($query);
        $request_succeeded = $sth->execute();
        $dbh = null;
    }

}

?>