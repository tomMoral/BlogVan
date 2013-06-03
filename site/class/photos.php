<?php
class Photos
{
    public $id_pics;
    public $pics_tab;

    function __construct($list_pics) {
        $this->id_pics = preg_split('/,/', $list_pics);
        $this->get_photo();
    }

    function get_photo()
    {
        $dbh = Database::connect();        
        $query = $dbh->prepare("SELECT * FROM `photos` WHERE `id` IN (?)");


        $query->execute(array(implode(',', array_map('intval', $this->id_pics))));
        
        $this->pics_tab=array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)){
            $this->pics_tab[]=$row;     
        }
    }
 
     static function add_photo($gps, $path)
     {
        $dbh = Database::connect();        
        $query = $dbh->prepare('CREATE TABLE IF NOT EXISTS `photos` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `gps` varchar(255) NOT NULL,
                          `date` date NOT NULL,
                          `path` text NOT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;'
            );
        $query->execute();

        $query = $dbh->prepare("INSERT INTO  `photos` (`gps`,`date`,`path`) 
                    VALUES (?, NOW(), ?)");
        $query->execute(array($gps,$path));
     }
}

?>
