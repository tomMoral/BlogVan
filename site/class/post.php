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
    function get_post($offset=0, $number=50)
    {
        $dbh = Database::connect();        
        $query = $dbh->prepare("SELECT * FROM `posts` ORDER BY time DESC LIMIT $offset,$number");


        $query->execute();
        $articles = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $row['voters'] = NULL;
            $row['results'] = NULL;
            $row['pictures'] = new Photos($row['pictures']);

            if($row['comments'] == '' or $row['comments'][0] != 'v') $row['comments'] = new Comments($row['comments']);
            else{
                $row['voters'] = array();
                $row['results'] = array();
                foreach(array_slice(preg_split('/,/', $row['comments']),1) as $vote)
                {
                    $vote = preg_split('/:/', $vote);
                    $row['voters'][] = $vote[0];
                    if(isset($row['results'][$vote[1]]))
                        $row['results'][$vote[1]] += 1;
                    else
                        $row['results'][$vote[1]] = 1;
                }
            }

            $row['body'] = $this->parse_post($row['body'], $row['pictures']->pics_tab, $row['id'],
                                             $row['results'], $row['voters']);
            $articles[] = $row;
        }
        return $articles;
    }

    static function add_post($gps, $titre, $body, $pictures, $comments='', $permission=0)
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
        
        if (!$query->execute(array($permission, $gps, $titre, 
                $pictures, $comments, $body))){
            return $query->errorInfo();
        }
        else return '';

    }

    static function add_comment($id, $user, $body, $vote =0)
    {
        $dbh = Database::connect();
        $query = $dbh->prepare("UPDATE `posts` SET `comments` = CASE WHEN `comments` = '' THEN :com ELSE CONCAT(CONCAT(`comments`,','),:com) END WHERE `id`=:post;");
        if($vote == 0){
            $id_com = Comments::add_comment($user, $body);
            $query->bindParam(':com', $id_com, PDO::PARAM_STR);
        }
        else{
            $tmp = $user.':'.$body;
            echo $tmp;
            $query->bindParam(':com' , $tmp );
        }
        $query->bindParam(':post', $id, PDO::PARAM_INT);
        $query->execute();
        return $id_com;
    }

    function parse_post($text, $pics, $id, $results = NULL, $voters=NULL)
    {
        $balise_text = array();
        $count = preg_match_all('/\[((?:[^:]+:)+[^\]]+)\]/', $text, $match);
        $balise_text = array();
        $balise_vote = array();
        if(count($match[0]) != 0)
        {
            $opts = preg_split('/:/', substr($match[0][0], 1 , -1));
            $balise_text[] = $match[0][0];
            $tmp = '<form action="new_comment.php" method="post">';
            foreach ($opts as $n => $prop){
                $tmp .= $prop . "<input type='radio' name='vote' value=".$n." checked='checked'>\n";
            }
            $tmp .= "<input type='submit' value='Vote'/>\n ";
            $tmp .= "<input type='hidden' name='id' value='" . $id. "'>\n";
            $tmp .= "</form>";
            $usr = user::getSessionUser();
            if(($usr == null || ($voters!=null) && in_array($usr->id, $voters))){
                $tmp = '<div class="results">';
                $n_votes = array_sum($results);
                foreach ($opts as $n => $prop) {
                    if(!isset($results[$n]))
                        $results[$n] = 0;
                    $tmp.= '<p>'.$prop.': '.number_format(100*$results[$n]/max($n_votes,1),1).'%</p>';
                }
                $tmp .= '</div>' ;
            }
            $balise_vote[] =$tmp;
        }

        $res = str_replace($balise_text, $balise_vote, $text);

        return $res;

    }
}