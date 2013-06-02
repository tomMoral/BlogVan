<?php
class photo
{
    function get_photo($list_pics)
    {
        mysql_connect("localhost", "root", "naruto") or
            die("Could not connect: " . mysql_error());
        mysql_select_db("photos");
     -   $result = mysql_query("SELECT * FROM `photo` WHERE `id` IN (" .
        implode(',', array_map('intval', $list_pics)) . ')';
        return $result;
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

