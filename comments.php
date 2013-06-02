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
        mysql_connect("localhost", "root", "") or
            die("Could not connect: " . mysql_error());
        mysql_select_db("Blog");
        $result = mysql_query("SELECT * FROM `comments` WHERE `id` IN (" .
        implode(',', array_map('intval', $this->id_coms)) . ')');
        
        $this->coms_tab=array();
        while ($row = mysql_fetch_row($result)){
            $this->coms_tab[$row[0]]=$row;     
        }
    }
 
     static function add_comment($user, $coms)
     {
        mysql_connect("localhost", "root", "") or
             die("Could not connect: " . mysql_error());

        mysql_select_db("Blog");

        if(! mysql_query("SHOW table LIKE 'comments'"))
            mysql_query('CREATE TABLE IF NOT EXISTS `comments` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
                            `time` date NOT NULL,
                            `user` varchar(255) DEFAULT \'""\',
                            `body` text NOT NULL,
                            PRIMARY KEY (`id`)
                            )  ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4'
            );
      
        $query = ("INSERT INTO  `comments` (
                    `user` , `time` ,  `body` ) 
                    VALUES ('$user', NOW(), '$coms' )");
        mysql_query($query);
        echo $query.'<br/>';
     }
}

?>