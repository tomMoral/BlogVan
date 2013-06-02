<?php
class post
{
    function get_post()
    {
        mysql_connect("localhost", "root", "naruto") or
            die("Could not connect: " . mysql_error());
        mysql_select_db("Blog");

        $result = mysql_query("SELECT * FROM `post` LIMIT 0,10");
        return $result;
    }

    function add_post($gps, $titre, $pics, $body)
    {
        mysql_connect("localhost", "root", "naruto") or
            die("Could not connect: " . mysql_error());
        mysql_select_db("Blog");
      
        mysql_query("INSERT INTO  `post` (  `time` ,  `gps` ,  `title` ,  `Pics` , `body` ) 
                     VALUES ( NOW() ,  '$gps' ,  '$titre',  '$pics',  '$body'
                     )");
    }
}
