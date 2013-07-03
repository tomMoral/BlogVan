<?php

class Posts {

    public $offset = 0;
    public $length = 10;
    public $post_tab;

    function __construct() {
        $this->post_tab = $this->get_post();
    }

    function get_post($offset = 0, $number = 50) {
        $dbh = Database::connect();
        $query = $dbh->prepare("SELECT * FROM `posts` ORDER BY time DESC LIMIT $offset,$number");
        $query->execute();
        $articles = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            //  $row['pictures'] = new Photos($row['pictures']);
            $row['comments'] = new Comments($row['comments']);
            $row['voters'] = array();
            $row['results'] = array();
            if ($row['vote'] != "") {
                //then $row['voters'] will contain the voters and $row['results'] the number of vote for each vote
                foreach (preg_split('/,/', $row['vote']) as $vote) {
                    $vote = preg_split('/:/', $vote);
                    $row['voters'][] = $vote[0];
                    if (isset($row['results'][$vote[1]]))
                        $row['results'][$vote[1]] += 1;
                    else
                        $row['results'][$vote[1]] = 1;
                }
            }
            $row['body'] = Posts::parse_post($row['body'], $row['id'], $row['results'], $row['voters']);
            $row['body_french'] = Posts::parse_post($row['body_french'], $row['id'], $row['results'], $row['voters']);
            $articles[] = $row;
        }
        return $articles;
    }

    static function add_post($gps, $titre, $titre_french, $body, $body_french, $pictures, $comments = '', $permission = 0) {
        $dbh = Database::connect();
        $query = $dbh->prepare('CREATE TABLE IF NOT EXISTS `posts` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `permission` tinyint(1) NOT NULL,
                          `time` datetime NOT NULL,
                          `gps` varchar(255) DEFAULT \'""\',
                          `title` varchar(255) NOT NULL DEFAULT \'""\',
                          `title_french` varchar(255) NOT NULL DEFAULT \'""\',
                          `pictures` text NOT NULL,
                          `comments` text NOT NULL,
                          `vote` text NOT NULL,
                          `body` text NOT NULL,
                          `body_french` text NOT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;'
        );
        $query->execute();

        $query = $dbh->prepare("INSERT INTO  `posts` ( 
            `permission`,`time`,`gps`,`title`,`title_french`,`pictures`,`comments`,`body`,`body_french`) 
                   VALUES (?,NOW(),?,?,?,?,?,?,?)");

        if (!$query->execute(array($permission, $gps, $titre, $titre_french,
                    $pictures, $comments, $body, $body_french))) {
            return $query->errorInfo();
        }
        else
            return '';
    }

    static function add_comment($id, $user, $body) {
        $dbh = Database::connect();
        $query = $dbh->prepare("UPDATE `posts` SET `comments` = CASE WHEN `comments` = '' THEN :com ELSE CONCAT(CONCAT(`comments`,','),:com) END WHERE `id`=:post;");
        $id_com = Comments::add_comment($user, $body);
        $query->bindParam(':com', $id_com, PDO::PARAM_STR);
        $query->bindParam(':post', $id, PDO::PARAM_INT);
        $query->execute();
        return $id_com;
    }
    
    static function next_id() {
        $query = "SELECT `id` FROM `posts` ORDER BY id DESC;";
        $dbh = Database::connect();
        $sth = $dbh->prepare($query);
        $sth->execute();
        $courant = $sth->fetch(PDO::FETCH_ASSOC);
        $id = $courant['id'] + 1;
        return $id;
    }

    static function vote($id, $user, $vote) {
        //update the votes with adding $user's vote
        $dbh = Database::connect();
        $query = "SELECT * FROM `posts` WHERE `id` = \"$id\" AND `vote` NOT LIKE \"%" . $user->id . ":%\"";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'posts');
        $sth->execute();
        $post = $sth->fetch();
        $sth->closeCursor();
        $votes = $post->vote;
        $votes.= $votes == '' ? '' : ',';
        $votes.=$user->id . ":" . $vote;
        $query = "UPDATE posts SET vote = \"$votes\" WHERE id = '$id'";
        $sth = $dbh->prepare($query);
        $sth->execute();
        $dbh = null;
    }

    static function parse_post($text, $id, $results = array(), $voters = NULL, $with_script = true) {
        $balise_text = array();
        $count = preg_match_all('/\[((?:[^::]+:)+[^\]]+)\]/', $text, $match);
        $balise_vote = array();
        $n_votes = array_sum($results);
        $usr = user::getSessionUser();
        if (count($match[0]) != 0) {
            $opts = preg_split('/::/', substr($match[0][0], 1, -1));
            $balise_text[] = $match[0][0];
            $tmp = '<form action="new_comment.php" method="post" id="form_' . $id . '">';
            $tmp .= '<fieldset>';
            $tmp .= "<div class='vote'>";
            $i = 0;
            $end1 = "</div><br/>";
            $end2 = "\n</fieldset>\n</from>";
            foreach ($opts as $n => $prop) {
                $tmp .= "<p>\n<input name='vote' value=".$n." type='radio'/>";
                $tmp .= "<span>".$prop."</span>";
                $tmp .= "<a href='#'>";
                if(!isset($results[$n]))
                    $results[$n] = 0;
                $tmp .= strval(number_format(100 * $results[$n] / max($n_votes, 1), 1));
                $tmp .= "% </a></p>";

                $i++;
            }
            $tmp .= $end1;
            $script = '';
            if ($with_script && $usr != null && !($voters!= null && in_array($usr->id, $voters))) {
                $tmp .= "<input type='submit' class='voteit' name='submibutton' title='Vote!' />";
                $tmp .= $end2;
                $tmp .="<script>
                    $(\"div.vote p\").append('<a class=\"vote-select\" href=\"#\">Select</a><a class=\"vote-deselect\" href=\"#\">Cancel</a>');
                    $(\".vote .vote-select\").click(
                        function(event) {
                            event.preventDefault();
                            var boxes = $(this).parent().parent().children();
                            boxes.removeClass(\"selected\");
                            $(this).parent().addClass(\"selected\");
                            $(this).parent().find(\":radio\").attr(\"checked\",\"checked\");
                        }
                    );

                    $(\".vote .vote-deselect\").click(
                        function(event) {
                            event.preventDefault();
                            $(this).parent().removeClass(\"selected\");
                            $(this).parent().find(\":radio\").removeAttr(\"checked\");
                        }
                      );
                </script>";
            }
            else
                $tmp .= $end2;
            $balise_vote[] = $tmp;
                
        }
        
        $res = str_replace($balise_text, $balise_vote, $text);

        return $res;
    }

}