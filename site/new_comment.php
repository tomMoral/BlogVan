
<?php
include_once("headerPHP.php");
$usr = user::getSessionUser();
echo 'post';
print_r($_POST);
	if (isset($_POST['body'])){
		$perm = (isset($_POST['permission']))?1:0;
		Posts::add_comment($_POST['id'], $usr->id, $_POST['body']);
		echo 'post ';
	}
	elseif (isset($_POST['vote'])) {
		echo $_SESSION[''];
		Posts::add_comment($_POST['id'], $usr->id, $_POST['vote'], 1);
	}
	echo 'post2';
	header("Location: index.php");
	exit;
?>