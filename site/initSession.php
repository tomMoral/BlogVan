<?php
ini_set('session.use_trans_sid', '0');
session_name("fatBlog");
session_start();
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = true;
}
// a mettre en entete de chaque page
?>
