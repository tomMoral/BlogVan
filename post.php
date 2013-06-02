<?php

include("photos.php");
include('comments.php');

class Posts
{
    public $offset = 0;
    public $length = 10;
    public $post_tab;

    function __construct()
    {
        $this->post_tab = $this->get_post();
    }
    function get_post($offset=0, $number=10)
    {
        mysql_connect("localhost", "root", "") or
            die("Could not connect: " . mysql_error());
        mysql_select_db("Blog");

        $result_post = mysql_query("SELECT * FROM `posts` LIMIT $offset,$number");

        $articles = array();
        while ($row = mysql_fetch_array($result_post, MYSQL_NUM)) {
            $row[5] = new Photos($row[5]);
            $row[6] = new Comments($row[6]);
            $row[7] = $this->parse_post($row[7], $row[5]->pics_tab);
            $articles[] = $row;
        }
        return $articles;
    }

    static function add_post($gps, $titre, $body, $pictures='', $comments='', $permission=0)
    {
        mysql_connect("localhost", "root", "") or
            die("Could not connect: " . mysql_error());
        mysql_select_db("Blog");

        if(!mysql_query("SHOW table LIKE 'posts'"))
            mysql_query('CREATE TABLE IF NOT EXISTS `posts` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `permission` tinyint(1) NOT NULL,
                          `time` date NOT NULL,
                          `gps` varchar(255) DEFAULT \'""\',
                          `title` varchar(255) NOT NULL DEFAULT \'""\',
                          `pictures` text NOT NULL,
                          `comments` text NOT NULL,
                          `body` text NOT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;'
            );

        $query = ("INSERT INTO  `posts` ( 
            `permission`,`time`,`gps`,`title`,`pictures`,`comments`,`body`) 
                   VALUES ( 
            $permission,NOW(),'$gps','$titre','$pictures','$comments','$body')");
        $r = mysql_query($query);
        echo $query.','.$r.'<br/>';
    }

    function parse_post($text, $pics)
    {

        $balise_pics = array();
        $match = array();
        $count = preg_match_all('/\[p\]/', $text, $match);
        foreach( array_slice($pics, 0,$count) as $p )
        {
            $url = $p[4];
            $user = $p[1];
            $date = $p[3];
            $balise_pics[] = "<img src='$url' alt='$user,  $date' id='pics_post'>";
        }
        $balise_text = array();
        foreach($match as $m){
            if(count($m) > 0) $balise_text[] = $m[0];
        }

        $res = str_replace($balise_text, $balise_pics, $text);
        return $res;

    }
}
