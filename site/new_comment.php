
<?php
echo 'post';
	if (isset($_POST['body'])){
		include("class/post.php");
		$perm = (isset($_POST['permission']))?1:0;
		Posts::add_comment($_POST['id'], 'User1', $_POST['body']);
		echo 'post ';
	}
	echo 'post2';
	header("Location: index.php");
	exit;
?>