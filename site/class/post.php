<?php

include_once('comments.php');

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
            $withscript = true;
            if ($row['vote'] != "") {
                //then $row['voters'] will contain the voters and $row['results'] the number of vote for each vote
                $rvote = $row['vote'];
                if ($row['vote'][0] == 'c') {
                    $withscript = false;
                    $rvote = substr($row['vote'], 1);
                }
                foreach (preg_split('/,/', $rvote) as $vote) {
                    $vote = preg_split('/:/', $vote);
                    $row['voters'][] = $vote[0];
                    if (isset($row['results'][$vote[1]]))
                        $row['results'][$vote[1]] += 1;
                    else
                        $row['results'][$vote[1]] = 1;
                }
            }
            $row['body'] = Posts::parse_post($row['body'], $row['id'], $row['results'], $row['voters'], $withscript);
            $row['body_french'] = Posts::parse_post($row['body_french'], $row['id'], $row['results'], $row['voters'], $withscript);
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
                          `like` text NOT NULL,
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

    static function modify_post($id, $gps, $titre, $titre_french, $body, $body_french, $permission) {
        $dbh = Database::connect();

        $query = $dbh->prepare("UPDATE `posts` SET  `title` = '$titre' ,  `title_french` = '$titre_french' ,  `body` = '$body',   `body_french` = '$body_french' ,   `permission` = '$permission' WHERE `id`=$id");

        if (!$query->execute()) {
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
        $n_votes = count($results) == 0 ? 0 : array_sum($results);
        $usr = user::getSessionUser();
        if (count($match[0]) != 0) {
            $opts = preg_split('/::/', substr($match[0][0], 1, -1));
            $balise_text[] = $match[0][0];
            $tmp = '<form action="new_comment.php" method="post" id="form_' . $id . '">';
            $tmp .= '<fieldset>';
            $tmp .= "<input type='hidden' name='id' value='$id'/>";
            $tmp .= "<div class='vote' id='vote_$id'>";
            $i = 0;
            $end1 = "</div><br/>";
            $end2 = "\n</fieldset>\n</form>";
            foreach ($opts as $n => $prop) {
                $tmp .= "<div class='prop'>\n<input name='vote' value=" . $n . " type='radio'/>";
                $tmp .= "<div class='vote_left'>" . $prop;
                $tmp .= "</div><div class='vote_right'>";
                $tmp .= "<span class='result'>";
                if (!isset($results[$n]))
                    $results[$n] = 0;
                $tmp .= strval(number_format(100 * $results[$n] / max($n_votes, 1), 1));
                $tmp .= "% </span></div></div>";

                $i++;
            }
            $tmp .= $end1;
            $tmp.="<script>
                    $(document).ready(function(){
                     var maxHeight = Math.max($('#vote_$id .vote_left').height(), $('#vote_$id .vote_right').height());
   $('#vote_$id .prop').height(maxHeight+30);
                    });</script>";

            if ($with_script && $usr != null && !($voters != null && in_array($usr->id, $voters))) {
                $tmp .= "<div class='voteit'><input type='submit' name='submibutton' title='Vote!' /></div>";
                $tmp .= $end2;
                $tmp .= "<script>
    $(\"#vote_$id .vote_right\").append('<a class=\"vote-select\" href=\"#\">Select</a><a class=\"vote-deselect\" href=\"#\">Cancel</a>');
                    $(\".vote .vote-select\").click(
                        function(event) {
                            event.preventDefault();
                            var boxes = $(this).parent().parent().parent().children();
                            boxes.removeClass(\"selected\");
                            $(this).parent().parent().addClass(\"selected\");
                            $(this).parent().parent().find(\":radio\").attr(\"checked\",\"checked\");
                        }
                    );

                    $(\".vote .vote-deselect\").click(
                        function(event) {
                            event.preventDefault();
                            $(this).parent().parent().removeClass(\"selected\");
                            $(this).parent().parent().find(\":radio\").removeAttr(\"checked\");
                        }
                      );
                </script>"
                ;
            }
            else{
                $tmp .= $end2;
                $tmp .= "<script>
                    $(document).ready(function(){
                     var maxHeight = Math.max($('#vote_$id .vote_left').height(), $('#vote_$id .vote_right').height());
   $('#vote_$id .prop').height(maxHeight+30);
<<<<<<< HEAD
                    }); </script>";
=======
                    }); </script>"; 
>>>>>>> Resize vote now works for results
            }
            $balise_vote[] = $tmp;
        }

        $res = str_replace($balise_text, $balise_vote, $text);

        return $res;
    }

    static function delete($id) {
        $post = Posts::get_by_id($id);
        if ($post != null) {
            $coms = explode(',', $post->comments);
            foreach ($coms as $com_id) {
                Comments::delete($com_id);
            }
            $dbh = Database::connect();
            $query = "DELETE FROM posts WHERE id='$id'";
            $sth = $dbh->prepare($query);
            $request_succeeded = $sth->execute();
            $dbh = null;
        }
    }

    static function close($id) {
        $dbh = Database::connect();
        $query = "SELECT `vote` FROM posts WHERE id=$id";
        $sth = $dbh->prepare($query);
        $request_succeeded = $sth->execute();
        $vote = $sth->fetch();
        if ($vote['vote'][0] != 'c') {
            $query = "UPDATE `posts` SET `vote`=CONCAT('c',`vote`) WHERE id=$id";
            $sth = $dbh->prepare($query);
            $request_succeeded = $sth->execute();
        }
        $dbh = null;
    }

    static function get_by_id($id) {
        $dbh = Database::connect();
        $query = "SELECT * FROM `posts` WHERE `id` = \"$id\"";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'posts');
        $sth->execute();
        $com = $sth->fetch();
        $sth->closeCursor();
        $dbh = null;
        return $com;
    }

    function add_like($user_id) {
        $dbh = Database::connect();
        $like = $this->like;
        $user_who_liked = explode(",", $like);
        $has_already_liked = false;
        foreach ($user_who_liked as $user) {
            if ($user == $user_id . "") {
                $has_already_liked = true;
            }
        }
        if (!$has_already_liked) {
            $like = $like == "" ? $user_id : $like . "," . $user_id;
            $id = $this->id;
            $dbh = Database::connect();
            $query = $dbh->prepare("UPDATE `posts` SET `like` = '$like' WHERE `id`=$id;");
            $query->execute();
            $dbh = null;
        }
    }

}
