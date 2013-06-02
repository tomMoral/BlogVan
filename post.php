<?php
class post
{
    function get_post()
    {
        mysql_connect("localhost", "root", "naruto") or
            die("Could not connect: " . mysql_error());
        mysql_select_db("Blog");

        $result_post = mysql_query("SELECT * FROM `post` LIMIT 0,10");

        mysql_select_db("photos");
        $articles = array();

        while ($row = mysql_fetch_array($result_post, MYSQL_NUM)) {
            $result_pics = mysql_query("SELECT * FROM `photos` WHERE `id` IN (" .
                                        implode(',', array_map('intval',
                                        preg_split('/,/', $row[4]))) . ')');
        
            $pics=array();
            while ($pic_row = mysql_fetch_row($result_pics)) $pics[]=$pic_row;
            $row[4] = $pics;
            $articles[] = $row;
        }
        return $articles;
    }

    function add_post($gps, $titre, $pics, $body)
    {
        mysql_connect("localhost", "root", "naruto") or
            die("Could not connect: " . mysql_error());
        mysql_select_db("Blog");
      
        mysql_query("INSERT INTO  `post` (`time`, `gps`, `title`, `Pics`, `body`) 
                     VALUES ( NOW() ,'$gps','$titre','$pics','$body')");
    }
}
