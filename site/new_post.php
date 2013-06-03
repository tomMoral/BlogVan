
<?php
	if (!isset($_POST['post'])){
		include("header.php");
?>

<div id="post">
	<form action="new_post.php" method="post">
		Titre: <input type="text" name='title'><br>
		Post: <input type="text" name="post"><br>
		Permission: <input type="checkbox" name="permission" value=1 checked="checked">All<br>
		<input type="submit">
	</form>
</div>
<?php
	}
	else{
		include("class/post.php");
		$perm = (isset($_POST['permission']))?1:0;
		Posts::add_post('GPS1', $_POST['title'], $_POST['post'], '','',$perm);
		header("Location: index.php");
		exit;
	}
?>