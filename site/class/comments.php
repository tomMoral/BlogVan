<?php
class Comments
{
    public $id_com;
    public $coms_tab;

    function __construct($list_coms="") {
        $this->id_coms = preg_split('/,/', $list_coms);
        $this->get_com();
    }

    function get_com()
    {
        $dbh = Database::connect();
        $newparams = array();
        foreach ($this->id_coms as $n => $val){ $newparams[] = ":id_$n"; }
        $query = $dbh->prepare("SELECT * FROM `comments` WHERE `id` IN (" . implode(", ",$newparams). ")");
        foreach ($this->id_coms as $n => $val){
            $value = intval($val);
            $query->bindParam(":id_$n", $value, PDO::PARAM_INT);
        }

        $query->execute();

        $this->coms_tab=array();
        while ($row =  $query->fetch(PDO::FETCH_ASSOC)){
            $this->coms_tab[]=$row;
        }
    }
 
     static function add_comment($user, $body)
     {
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
        $query->execute(array($user,$body));

        return $dbh->lastInsertId() ;
     }
     
     static function get_com_by_id($id)
    {
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
}

?>