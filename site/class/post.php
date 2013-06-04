<?php

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
        $dbh = Database::connect();        
        $query = $dbh->prepare("SELECT * FROM `posts` ORDER BY time DESC LIMIT $offset,$number");


        $query->execute();
        $articles = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $row['pictures'] = new Photos($row['pictures']);
            $row['comments'] = new Comments($row['comments']);
            $row['body'] = $this->parse_post($row['body'], $row['pictures']->pics_tab);
            $articles[] = $row;
        }
        return $articles;
    }

    static function add_post($gps, $titre, $body, $pictures='', $comments='', $permission=0)
    {
        $dbh = Database::connect();        
        $query = $dbh->prepare('CREATE TABLE IF NOT EXISTS `posts` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `permission` tinyint(1) NOT NULL,
                          `time` datetime NOT NULL,
                          `gps` varchar(255) DEFAULT \'""\',
                          `title` varchar(255) NOT NULL DEFAULT \'""\',
                          `pictures` text NOT NULL,
                          `comments` text NOT NULL,
                          `body` text NOT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;'
            );
        $query->execute();

        $query = $dbh->prepare("INSERT INTO  `posts` ( 
            `permission`,`time`,`gps`,`title`,`pictures`,`comments`,`body`) 
                   VALUES (?,NOW(),?,?,?,?,?)");
        
        $query->execute(array($permission, $gps, $titre, 
            $pictures, $comments, $body));
    }

    static function add_comment($id, $user, $body)
    {

        $id_com = Comments::add_comment($user, $body);
        echo $id_com;
        $dbh = Database::connect();
        $query = $dbh->prepare("UPDATE `posts` SET `comments` = CASE WHEN `comments` = '' THEN :com ELSE CONCAT(CONCAT(`comments`,','),:com) END WHERE `id`=:post;");
        $query->bindParam(':com', $id_com, PDO::PARAM_STR);
        $query->bindParam(':post', $id, PDO::PARAM_INT);
        $query->execute();
    }

    function parse_post($text, $pics)
    {

        $balise_pics = array();
        $match = array();
        $count = preg_match_all('/\[p\]/', $text, $match);
        foreach( array_slice($pics, 0,$count) as $p )
        {
            $url = $p['path'];
            $date = $p['time'];
            $balise_pics[] = "<img src='$url' alt='$date' id='pics_post'>";
        }
        $balise_text = array();
        foreach($match as $m){
            if(count($m) > 0) $balise_text[] = $m[0];
        }

        $res = str_replace($balise_text, $balise_pics, $text);
        return $res;

    }
}
