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
        mysql_connect("localhost", "root", "naruto") or
            die("Could not connect: " . mysql_error());
        mysql_select_db("Blog");
        $result = mysql_query("SELECT * FROM `photos` WHERE `id` IN (" .
        implode(',', array_map('intval', $this->id_pics)) . ')');
        
        $this->pics_tab=array();
        while ($row = mysql_fetch_row($result)){
            $this->pics_tab[$row[0]]=$row;     
        }
    }
 
     static function add_photo($gps, $path)
     {
        mysql_connect("localhost", "root", "naruto") or
             die("Could not connect: " . mysql_error());
        mysql_select_db("Blog");

        if(!mysql_query("SHOW table LIKE 'photos'"))
            mysql_query('CREATE TABLE IF NOT EXISTS `photos` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `gps` varchar(255) NOT NULL,
                          `date` date NOT NULL,
                          `path` text NOT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;'
            );
      
        $query = ("INSERT INTO  `photos` (`gps`,`date`,`path`) 
                    VALUES ('$gps', NOW(), '$path')");
        mysql_query($query);
        echo $query.'<br/>';
     }
}

?>