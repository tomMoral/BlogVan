<?php
include("headerPHP.php");
$user = user::getSessionUser();
if ($user != null && $user->type == 2) {
    if (!isset($_POST['post'])) {
        htmlHeader("blog");
        ?>

<div id="post">
	<form action="new_post.php" method="post" enctype="multipart/form-data" id="np">
		Titre: <input type="text" name="title"></br>
		Post: <textarea type="text" name="post" placeholder='New Post, insert photo at [pi]'></textarea><br>
		Permission: <input type="checkbox" name="permission" value=1 checked="checked">All<br>
		<input type="submit"></br>
		pics :</br><input type="file" name="pic1" id="pic1"></br>
	</form>
</div>

<script>
	function on_change($input, $i){
		return (function(){
			if($input.value == '') return;
			var $newFile = document.createElement('input');
			$newFile.type = "file";
			$newFile.name = "pic" + $i;
			$newFile.onchange = on_change($newFile, $i+1);
			$input.parentNode.appendChild($newFile);
			$input.parentNode.appendChild(document.createElement('br'));

		})
	}
	$inp = document.getElementById('pic1')
	$inp.onchange = on_change($inp, 2);
</script>
<?php
	}
	else{
		$i = 1;
		$pics = '';
		$dossier = 'pics_up/';
		echo 'hello';
		print_r($_FILES);
		while(isset($_FILES["pic$i"]))
		{ 
			echo $i;
			echo $_FILES["pic$i"]['name'];
		     $fichier = basename($_FILES["pic$i"]['name']);
		     if(move_uploaded_file($_FILES["pic$i"]['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
		     {
		          echo 'Upload effectué avec succès !';
		          $pics .='.'.Photos::add_photo('', $dossier . $fichier);
		     }
		     else //Sinon (la fonction renvoie FALSE).
		     {
		          echo 'Echec de l\'upload !';
		     }
		     $i += 1;
		}
		$perm = (isset($_POST['permission']))?1:0;
		$count = preg_match_all('/\[([^:]:)+[^\]]\]/', $_POST['post']);
		if($count ==0) $comments = '';
		else $comments = 'v';
		echo $pics;
		$return = Posts::add_post('GPS1', $_POST['title'], $_POST['post'], $pics, $comments, $perm);
		if ($return == '')
			header("Location: index.php");
		else
			echo $return;
		exit;
	}
}
?>
