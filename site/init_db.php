 <?php
 	include('post.php');

 	Posts::add_post('GPS1','Titre cool 1',
 		'Un super post de l\\\'espace [p],</br> avec des photos [p]', '3,2');
 	Posts::add_post('GPS1','Titre cool 2', 
 		'Un second post sur la couleur du van sans photo','', '1,2');
 	Posts::add_post('GPS1','Titre cool 3', 'Photo :) [p]','1','17');
 	Posts::add_post('GPS1','Moins de titre',
 		'Un test de la merde du user [p]', '12','1,2,3');

 	Photos::add_photo('GPS1', 'images/photo1.jpg');
 	Photos::add_photo('GPS2', 'images/photo2.jpg');
 	Photos::add_photo('GPS3', 'images/photo3.jpg');
 	Photos::add_photo('GPS4', 'images/photo4.jpg');


 	Comments::add_comment('tom', 'C\\\'est genial non?');
 	Comments::add_comment('gui', 'Yep je kiffe !!');
 	Comments::add_comment('tom', 'On va mettre des likes aussi?');
 	Comments::add_comment('gui', 'Oui oui oui oui!');

 	echo 'RAS'
 	?>