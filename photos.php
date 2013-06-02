<?php
class photo
{
    function get_photo($list_pics)
    {
        mysql_connect("localhost", "root", "") or
            die("Could not connect: " . mysql_error());
        mysql_select_db("photos");
        $result = mysql_query("SELECT * FROM `photos` WHERE `id` IN (" .
        implode(',', array_map('intval', preg_split('/,/', $list_pics))) . ')');
        
        $pics=array();
        while ($row = mysql_fetch_row($result)) $pics[]=$row;        
        return $pics;
    }
 
     function add_pics($gps, $titre, $pics, $body)
     {
        mysql_connect("localhost", "root", "naruto") or
             die("Could not connect: " .
        mysql_error());
        mysql_select_db("Blog");
      
        mysql_query("INSERT INTO  `post` (
                    'Time` ,  `gps` ,  `title` ,  `Pics` , `body` ) 
                    VALUES ( NOW() , $gps' ,  '$titre',  '$pics',  '$body' )");
     }
}

