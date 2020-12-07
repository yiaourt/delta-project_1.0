<?php class home_gestion{
	
	// // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 
	// La page d'accueil, ci-dessous
	public function afficheHome(){
		$bdd = connectSql();
		
		echo "<div class='titleblue'>Gestion de la page d'accueil</div><div id='border2'></div><br/>";
		echo '<div class="topiccontent">';
		echo "<a href=panel_admin.php?menu=topic&mod=home_new>Ajouter un nouvelle article</a>
			|<a href=panel_admin.php?menu=topic&mod=home_preview>Voir les brouillons</a>
			|<a href=>Paramètres Page d'accueil</a>";
		echo '</div>';
		echo '</div>'; // <div id="menumarg">
		
		$req = $bdd->query('SELECT * FROM home');
		$count_sql=0;
		
		while($sql = $req->fetch()){
			echo '<div id="menumarg">';
			echo '<u>id :</u> '.$sql['id'].'<hr width=\"100%\" size=\"1\" />';
			echo '<div id="bordernomarg"></div>';
			echo $sql['contenu'];
			echo '<hr width=\"100%\" size=\"1\" /><a href=panel_admin.php?menu=topic&mod=mod&id='.$sql['id'].'>Modifier</a>
				| <a href=panel_admin.php?menu=topic&mod=delete&id='.$sql['id'].'>Supprimer</a> ';
			echo '</div>';
			$count_sql++;
		}
		if($count_sql == 0){
			echo '<div id="menumarg">';
			echo '<div id="bordernomarg"></div>';
			echo 'Aucun articles présent, vous pouvez en créer un <a href=panel_admin.php?menu=topic&mod=home_new>ici</a>';
			echo '<div id="border"></div>';
		}
		$sql = $req->closeCursor();
		
		echo '</div>'; // <div id="menumarg">
	}
	
	// // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 
	// La page qui affiche les brouillons 'home_preview', ci-dessous
	public function afficheHomebrouillons(){
		$bdd = connectSql();
		echo "<div class='titleblue'>Gestion de la page d'accueil</div><div id='border2'></div><br/>";
		echo '<div class="topiccontent">';
		echo "<a href=panel_admin.php?menu=topic&mod=home_new>Ajouter un nouveau brouillon</a>|<a href=panel_admin.php?menu=topic&mod=home>Voir articles page d'accueil</a>|<a href=>Paramètres Page d'accueil</a>";
		echo '</div>';
		echo '</div>'; // <div id="menumarg">
		
		if(isset($_GET['add']) && isset($_GET['id'])){
			
			$req1 = $bdd->query('SELECT * FROM home_preview WHERE id = \''.$_GET['id'].'\'');
			$sql_h_p = $req1->fetch();
			
			$req2 = $bdd->prepare('INSERT INTO home(contenu) VALUES(:contenu)');

			$req2->execute(array(
				'contenu' => $_POST['contenu']
			));
			$sql_h_p = $req1->closeCursor(); // On ferme la requête !!
			$req2->closeCursor(); // On ferme la requête !!
			
			// Et on oublis pas de supprimer l'article une fois ajouter.
			try{
				// set the PDO error mode to exception
				$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				// sql to delete a record
				$sql_delete = $bdd->prepare("DELETE FROM home_preview WHERE id= ?");
				
				// use exec() because no results are returned
				$sql_delete->execute(array($_GET['id']));
			}
						
			catch(PDOException $e){
				echo $sql_delete . "<br />" . $e->getMessage();
			}
			$sql_delete->closeCursor();
			echo "<br/><p id='annonce'>Le brouillon id = ".$_GET['id']."<br/>";
			echo 'A bien était ajouter aux articles. <br/><a href="panel_admin.php?menu=topic&mod=home">Retour ...</a></p>';
			exit;
		}
		
		$req = $bdd->query('SELECT * FROM home_preview');
		$count_sql=0;
		
		while($sql = $req->fetch()){
			echo '<div id="menumarg">';
			echo '<u>id :</u> '.$sql['id'].'<hr width=\"100%\" size=\"1\" />';
			echo '<div id="bordernomarg"></div>';
			echo $sql['contenu'];
			echo '<hr width=\"100%\" size=\"1\" /><a href=panel_admin.php?menu=topic&mod=mod&preview=1&id='.$sql['id'].'>Modifier</a> 
				| <a href=panel_admin.php?menu=topic&mod=delete&preview=1&id='.$sql['id'].'>Supprimer</a> 
				| <a href=panel_admin.php?menu=topic&mod=home_preview&id='.$sql['id'].'&add=1>Ajouter aux articles</a>';
			echo '</div>';
			$count_sql++;
		}
		if($count_sql == 0){
			echo '<div id="menumarg">';
			echo '<div id="bordernomarg"></div>';
			echo 'Aucun articles <b>brouillon</b> présent, vous pouvez en créer un <a href=panel_admin.php?menu=topic&mod=home_new>ici</a>';
			echo '<div id="border"></div>';
		}
		$sql = $req->closeCursor();
		
		echo '</div>'; // <div id="menumarg">
	}
	
	// // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 
	// La page qui permet d'ajouter un nouvel article 'home' ou 'home_preview'
	public function newHome(){
		$bdd = connectSql();
		echo '<br/>';
		echo '</div>'; // <div id="menumarg">
		echo '<div id="menumarg">';
		echo '<div id="bordernomarg"></div>';
		echo '<div id="border"></div>';
		
		echo "<div class='underline'>Ajouter un nouvelle article sur la page d'accueil :</div><br/>";
		
		echo '<form action="panel_admin.php?menu=topic&mod=new&submit=1" method="post">';
		
		echo '<textarea type="text" class="big" name="new_home" id="editor">';
		if(!empty($_SESSION['preview_home'])){ echo $_SESSION['preview_home'];}
		echo '</textarea><br/>';

		echo '<input type="submit" name="preview" value="Aperçu" />';
		
		echo '| <input type="submit" name="save_preview" value="Enregistrer dans brouillon" />';
		echo '| <input type="submit" name="save" value="Enregistrer" /></div>';

		// on appel ckeditor4 pour changer l'éditeur de texte "<textarea>"
		echo '<script src="inc/create_ckeditor.js"></script>';
		
		echo '</form>';
		echo '</div>'; // <div id="menumarg">
	}
	
	public function modHome(){
		$bdd = connectSql();
		if(!empty($_GET['id'])){
			$sql_id = $_GET['id'];
			
			if(!empty($_GET['preview']) && $_GET['preview'] == 1){ // On modifie un brouillons, ci-dessous
				
				$req = $bdd->query('SELECT * FROM home_preview WHERE id = \''.$sql_id.'\'');
				$sql = $req->fetch();
			}else{ // ici on modifie l'article, ci-dessous
				
				$req = $bdd->query('SELECT * FROM home WHERE id = \''.$sql_id.'\'');
				$sql = $req->fetch();
			}
		}else{
			echo '<br/><br/><div id="borderbgnomarg"><div id="bordercenter">';
			echo '<div id="annonce">Erreur, Aucune id de sujet trouver, l\'article n\'existe pas ou à été supprimer.</div>';
			echo '<a href="panel_admin.php?menu=topic&mod=home">Retour ...</a>';
			echo '</div></div></div>';
			exit;
		}
		echo '<br/>';
		echo '</div>'; // <div id="menumarg">
		echo '<div id="menumarg">';
		echo '<div id="bordernomarg"></div>';
		echo '<div id="border"></div>';
		
		echo "<div class='underline'>Modifier un article sur la page d'accueil :</div><br/>";
		
		if(!empty($_GET['preview']) && $_GET['preview'] == 1){ // On modifie un brouillons, ci-dessous
			echo '<form action="panel_admin.php?menu=topic&mod=1&preview=1&id='.$sql_id.'" method="post">';
		}else{ // On modifie un article, ci-dessous
			echo '<form action="panel_admin.php?menu=topic&mod=1&id='.$sql_id.'" method="post">';
		}

		
		echo '<textarea type="text" class="big" name="new_home" id="editor">';
		echo $sql['contenu'];
		echo '</textarea><br/>';
		
		// on appel ckeditor4 pour changer l'éditeur de texte "<textarea>"
		echo '<script src="inc/create_ckeditor.js"></script>';
		
		echo '<input type="submit" name="update" value="Enregistrer les modifications" /></div>';
		
		echo '</form>';
		echo '</div>'; // <div id="menumarg">
		$req->closeCursor();
		exit;
	}
	
	// // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 
	// La page qui supprimme un article 'home' ou 'home_preview'
	public function deleteHome(){
		$bdd = connectSql();
		if(!empty($_GET['preview']) && $_GET['preview'] == 1){
			if(!empty($_GET['a'])){ // delete un brouillons
				
				try{
					// set the PDO error mode to exception
					$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					// sql to delete a record
					$sql = $bdd->prepare("DELETE FROM home_preview WHERE id= ?");

					// use exec() because no results are returned
					$sql->execute(array($_GET['id']));
					echo "<br/><p id='annonce'>L'article id = ". $_GET['id']." à bien été suprimmer.";
					echo "<br /> <a href='panel_admin.php?menu=topic&mod=home'>Rafraichir la page</a></p>";
				}
						
				catch(PDOException $e){
					echo $sql . "<br />" . $e->getMessage();
				}
				$sql->closeCursor();
				exit;
			}
		}else{
			if(!empty($_GET['a'])){ // delete un article 'home'
				
				try{
					// set the PDO error mode to exception
					$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					// sql to delete a record
					$sql = $bdd->prepare("DELETE FROM home WHERE id= ?");

					// use exec() because no results are returned
					$sql->execute(array($_GET['id']));
					echo "<br/><p id='annonce'>L'article id = ". $_GET['id']." à bien été suprimmer.";
					echo "<br /> <a href='panel_admin.php?menu=topic&mod=home'>Rafraichir la page</a></p>";
				}
						
				catch(PDOException $e){
					echo $sql . "<br />" . $e->getMessage();
				}
				$sql->closeCursor();
				exit;
			}
		}
		
		// Ici on vérifie que l'utilisateur veut bien supprimer cette article
		if(!empty($_GET['id'])){
			if(!empty($_GET['preview']) && $_GET['preview'] == 1){ // Donc, si on veut supprimer un brouillon
				echo "<br/><p id='annonce'>Voulez-vous réellement supprimer l'article à l'id : ". $_GET['id'] ." ?<br/><br/>";
				echo '<a href="panel_admin.php?menu=topic&mod=delete&preview=1&id='.$_GET['id'].'&a=y">Oui</a> |-| <a href="panel_admin.php?menu=topic">Non</a><p/>';
			}else{ // Sinon, on suprimme un article
				echo "<br/><p id='annonce'>Voulez-vous réellement supprimer l'article à l'id : ". $_GET['id'] ." ?<br/><br/>";
				echo '<a href="panel_admin.php?menu=topic&mod=delete&id='.$_GET['id'].'&a=y">Oui</a> |-| <a href="panel_admin.php?menu=topic">Non</a><p/>';
			}
		}else{
			echo '<div id="borderbgnomarg"><div id="bordercenter">';
			echo '<div id="center"><p id="annonce">Erreur : Aucune article trouver pour l\'id '.$_GET['id'].'.</p>';
			echo '<a href="panel_admin.php?id='.$_GET['id'].'">Retour ...</a>';
			echo '</div></div></div>';
			exit;
		}
	}
	
	// // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 
	// La page qui submit une nouvelle article
	public function submitHome(){
		$bdd = connectSql();
		if(!empty($_POST['save'])){
			$_POST = array(
				'contenu' => $_POST['new_home'],
				'save' => 'Envoyer'
			);
		}

		if(!empty($_POST['save_preview'])){
			$_POST = array(
				'contenu' => $_POST['new_home'],
				'save_preview' => 'Envoyer'
			);
		}

		if(!empty($_POST['preview'])){
			$_POST = array(
				'contenu' => $_POST['new_home'],
				'preview' => 'Aperçu'
			);
		}

		if (!empty($_POST['preview'])) {
			// On récupere le contenu...
			$post_preview = $_POST['contenu'];

			echo '<div id="menumarg">';
			$_SESSION['preview_home'] = $post_preview;
			
			echo '<- <a href="panel_admin.php?menu=topic&mod=home_new">Retour</a>';
			echo '<div id="border"></div>';
			echo $post_preview;
			echo '</div>';
			exit;
		}

		elseif (!empty($_POST['save_preview'])) {

			$post_preview = $_POST['contenu'];
			// Puis on enregistre l'article sur la bases de données dans brouillon ici
			// Puis on écrit les données sur la base "delta" 
			$req = $bdd->prepare('INSERT INTO home_preview (contenu) VALUES(:contenu)');

			$req->execute(array(
				'contenu' => $post_preview
			));
			
			echo '<div id="borderbgnomarg"><div id="bordercenter">';
			echo '<div id="center"><p>Article enregistrer dans les brouillons.</p>';
			echo '<a href="panel_admin.php?menu=topic&mod=home">Retour ...</a>';
			echo '</div></div></div>';
			$req->closeCursor();
			exit;
		}
		elseif (!empty($_POST['save'])) {
			$post_preview = $_POST['contenu'];
			
			// on enregistre l'article sur la bases de données dans brouillon ici
			// Puis on écrit les données sur la base "delta" 
			$req = $bdd->prepare('INSERT INTO home (contenu) VALUES(:contenu)');

			$req->execute(array(
				'contenu' => $post_preview
			));
			
			echo '<div id="borderbgnomarg"><div id="bordercenter">';
			echo '<div id="center"><p>Article ajouter avec succés.</p>';
			echo '<a href="panel_admin.php?menu=topic&mod=home">Retour ...</a>';
			echo '</div></div></div>';
			$req->closeCursor();
			exit;
			
		}else{
		 
			echo '<div id="annonce">Erreur Impossible!</div>';
			echo '<a href="panel_admin.php?menu=topic&mod=home">Retour ...</a>';
		 
		}
	}
	
	// // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 
	// La page qui update un nouvelle article
	public function updateHome(){
		$bdd = connectSql();
		
		if(empty($_POST['new_home'])){
			echo '<br/><br/><div id="borderbgnomarg"><div id="bordercenter">';
			echo '<div id="annonce">Erreur, Aucun contenu rentrer dans l\'éditeur de texte.</div>';
			echo '<a href="panel_admin.php?menu=topic&mod=home">Retour ...</a>';
			echo '</div></div></div>';
			exit;
		}
		if(!empty($_GET['id'])){

			if(!empty($_GET['preview']) && $_GET['preview'] == 1){ // Si c'est un brouillon ..
				$req = $bdd->prepare('SELECT * FROM home_preview WHERE id = :id');
			}else{	// Si c'est un article ..
				$req = $bdd->prepare('SELECT * FROM home WHERE id = :id');
			}
			$req->execute(array(
				'id' => $_GET['id']
			));
			$sql = $req->fetch();
			$req->closeCursor();
			
			if(!empty($sql['id'])){

				// On récupere le contenu
				$contenu = $_POST['new_home'];
			
				if(!empty($_GET['preview']) && $_GET['preview'] == 1){ // Si c'est un brouillon ..
					$req = $bdd->prepare('UPDATE home_preview SET contenu = :contenu WHERE id = :id');
				}else{	// Si c'est un article ..
					$req = $bdd->prepare('UPDATE home SET contenu = :contenu WHERE id = :id');
				}
			
				$req->execute(array(
					'contenu' => $contenu,
					'id' => $sql['id']
				));
				echo '<div id="borderbgnomarg"><div id="bordercenter">';
				
				if(!empty($_GET['preview']) && $_GET['preview'] == 1){ // Si c'est un brouillon ..
					echo '<div id="center"><p>Brouillon modifier avec succés.</p>';
				}else{	// Si c'est un article ..
					echo '<div id="center"><p>Article modifier avec succés.</p>';
				}
				
				echo '<a href="panel_admin.php?menu=topic&mod=home">Retour ...</a>';
				echo '</div></div></div>';
				$req->closeCursor();
				exit;
			}else{
				echo '<br/><div id="annonce">Erreur : L\'article n\'existe pas.</div>';
				$req->closeCursor();
				exit;
			}
		}
	}
}
?>