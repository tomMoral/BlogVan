<?php
class Comments
{
    public $id_com;
    public $coms_tab;

    function __construct($list_coms) {
        $this->id_coms = preg_split('/,/', $list_coms);
        $this->get_com();
    }

    function get_com()
    {
        $dbh = Database::connect();        
        $query = $dbh->prepare("SELECT * FROM `comments` WHERE `id` IN (?)");


        $query->execute(array(implode(',', array_map('intval', $this->id_coms))));
        $result = mysql_query();
        
        $this->coms_tab=array();
        while ($row =  $query->fetch(PDO::FETCH_ASSOC)){
            $this->coms_tab[]=$row;     
        }
    }
 
     static function add_comment($user, $coms)
     {
        $dbh = Database::connect();        
        $query = $dbh->prepare('CREATE TABLE IF NOT EXISTS `comments` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
                            `time` date NOT NULL,
                            `user` varchar(255) DEFAULT \'""\',
                            `body` text NOT NULL,
                            PRIMARY KEY (`id`)
                            )  ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4'
            );
        $query->execute();

        $query = $dbh->prepare("INSERT INTO  `comments` (
                    `user` , `time` ,  `body` ) 
                    VALUES ('$user', NOW(), '$coms' )");
        $query->execute(array($user,$coms));
     }
}

?>