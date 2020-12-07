<?php 
class comment{

	public function submitComment(){
		$bdd = connectSql();

		//!\\ L'id récuperer ici est l'id de la sous categorie
		if(!empty($_GET['id']) && !empty($_GET['topic']) && $_GET['topic'] == 'new_comment'){

			// On aura besoin de récupérer l'id de la sous_catégorie pour pouvoir envoyez une redirection sur le topic ou se trouve le commentaire.
			$reqidscat = $bdd->prepare('SELECT id_s_cat FROM sous_forum_topic WHERE id = :id');
			$sqlidscat = $reqidscat->execute(array('id' => $_GET['id']));

			while($sqlidscat = $reqidscat->fetch()){
				$id_s_cat = $sqlidscat['id_s_cat'];
			}
			$reqidscat->closeCursor();
			
			if(!empty($_SESSION['pseudo'])){
				
				if(empty($_POST['comment'])){ // Si rien n'à était rentrer dans la zone de texte.
				 
					echo '<div id="annonce">Erreur: Vous devez remplir la zone de texte pour ajouter un commentaire.<br/>';
					echo '<a href="forum.php?id='.$id_s_cat.'&topic='.$_GET['id'].'">Retour ...</a></div>';
					exit;
				}
				if(isset($_POST['comment'])){
		
					$reqsftexistornot = $bdd->prepare('SELECT titre FROM sous_forum_topic WHERE id = :id');
					$sft = $reqsftexistornot->execute(array('id' => $_GET['id']));
					while($sft = $reqsftexistornot->fetch()){
						$userishere = $sft['titre'];
					}
					$reqsftexistornot->closeCursor();
					
					// J'affiche une érreur si la variable userishere est vide parce que l'id est érroner
					if(empty($userishere)){
						echo '<div id="annonce">Erreur : Le topic id = '.$_GET['id'].' n\'existe pas.<br/>';
						echo '<a href="forum.php">Retour ...</a></div>';
						exit;
					}
					
					// Je vérifie que le dernier message poster par l'utilisateur dépasse 5 minutes
					$timeneeded = $_SESSION['lastmsg']+02;
					if($timeneeded <= date('ymdhi') || $_SESSION['level'] == '0'){
						
						$contenuxhtml = $_POST['comment']; // On récupere le commentaire dans une variable.
						
						//  on enregistre le commentaire sur la bases de données 
						$reqnewcom = $bdd->prepare('INSERT INTO topic_comment 
						(topic_id, comment, auteur, date) VALUES(:topicid, :contenu, :auteur, NOW())');

						$reqnewcom->execute(array(
							'topicid' => $_GET['id'],
							'contenu' => $contenuxhtml,
							'auteur' => $_SESSION['pseudo']
						));
						$reqnewcom->closeCursor();
						
						// Je déclare l'heure actuel dans la variable session "lastmsg" comme étant l'heure du dernier message poster
						$_SESSION['lastmsg'] = date('ymdhi');
						
						echo '<div id="borderbgnomarg"><div id="bordercenter">';
						echo '<div id="center"><p>Commentaire ajouter avec succés.<br/>Vous allez être rediriger...</p>';
						echo '<script language="Javascript">setTimeout(function(){document.location.replace("forum.php?id='.$id_s_cat.'&topic='.$_GET['id'].'")}, 3200);</script>';
						echo '</div></div></div>';
						exit;
						
					}else{
						echo '<div id="annonce">Anti-spam : Vous avez poster un commentaire ou un sujet il y à moins de 2 minutes.<br/>Veuillez patienter, puis réessayer...<br/>';
						$_SESSION['redo'] = $_POST['comment'];
						echo '<script language="Javascript">setTimeout(function(){document.location.replace("forum.php?id='.$id_s_cat.'&topic='.$_GET['id'].'")}, 3200);</script>'; 
						exit;
					}
				}
			}else{
				echo '<div id="borderbgnomarg"><div id="bordercenter">';
				echo "<div id='center'><p>Vous devez être connecter pour envoyez un commentaire.</p>";
				echo '<a href="connexion.php">Connexion ...</a>';
				echo '</div></div></div>';
				exit;
			}
		}
	}
	
	public function modComment(){
		//!\\ L'id récuperer ici est l'id du topic
		if(!empty($_GET['id']) && !empty($_GET['topic']) && $_GET['topic'] == 'mod_comment'){
			$bdd = connectSql();
			
			if(empty($_SESSION['pseudo'])){
				echo '<div id="annonce">Erreur : Veuillez-vous connecter.';
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("connexion.php")}, 4200);</script>';
				exit;
			}
			
			$affichecomment = $bdd->prepare('SELECT * FROM topic_comment WHERE id = :commentid');
			$sql = $affichecomment->execute(array('commentid' => $_GET['id']));
			while($sql = $affichecomment->fetch()){
				$id = $sql['id'];
				$topic_id = $sql['topic_id'];
				$contenu = $sql['comment'];
				$auteur = $sql['auteur'];
			}
			$affichecomment->closeCursor();
			
			// J'affiche une érreur si la variable userishere est vide parce que l'id est érroner
			if(empty($id)){
				echo '<div id="annonce">Erreur : L\'id = '.$_GET['id'].' n\'existe pas.<br/>';
				echo '<a href="forum.php">Retour ...</a></div>';
				exit;
			}
			
			if(!empty($_SESSION) && $_SESSION['pseudo'] == $auteur || $_SESSION['level'] == '0'){
				
				$affichecomment = $bdd->prepare('SELECT * FROM topic_comment WHERE id = :commentid');
				$sql = $affichecomment->execute(array('commentid' => $_GET['id']));
				while($sql = $affichecomment->fetch()){
					$id = $sql['id'];
					$topic_id = $sql['topic_id'];
					$contenu = $sql['comment'];
					$auteur = $sql['auteur'];
				}
				$affichecomment->closeCursor();
				
				$whereisuser = $bdd->prepare('SELECT * FROM sous_forum_topic WHERE id = :id');
				$sous_cat = $whereisuser->execute(array('id' => $topic_id));
				while($sous_cat = $whereisuser->fetch()){
					$id_sous_cat = $sous_cat['id_s_cat'];
					$userishere = $sous_cat['id_s_cat'];
					$userisheretoo = $sous_cat['titre'];
				}
				$whereisuser->closeCursor();
				$whereisuser = $bdd->prepare('SELECT * FROM sous_forum WHERE id = :userishere');
				$sous_cat = $whereisuser->execute(array('userishere' => $userishere));
				while($sous_cat = $whereisuser->fetch()){
					
					$userishere = $sous_cat['titre']; // j'affiche ou ce situe l'utilisateur dans le forum dans une variable
				}
				$whereisuser->closeCursor();
				echo '<div id="left">
					  <a href="forum.php">Forum</a> /
					  <a href="forum.php?id='.$id_sous_cat.'">'.$userishere.'</a> /
					  <a href="forum.php?topic='.$topic_id.'">'.$userisheretoo.'</a>
					</div>';
				
				echo '<div id="menumarg">';
				echo '<div id="bordernomarg"></div>';
				echo '<div id="border"></div>';
				
				echo "<div class='underline'>Modifier un commentaire sur la sous-catégorie ''".$userishere."'' :</div>";
				
				echo '<form id="formsubmit" action="forum.php?id='.$id.'&topic=mod_comment&submit=1" method="post">';
				
				echo '<div id="newtopic">';
				
				echo '<div id="flex1"><br/><br/>
					  <textarea name="contenu" id="editor">';
				if(!empty($_SESSION['redo'])){
					echo $_SESSION['redo'];
					$_SESSION['redo'] = '';
				}else{
					$_SESSION['redo'] = '';
					echo $contenu;
				}
				echo '</textarea></div>';
				echo '<div id="flex3"><br/>';
				echo '<button id="savebutton">Enregistrer</button>';
				
				// on appel ckeditor4 pour changer l'éditeur de texte "<textarea>"
				echo '<script src="inc/create_ckeditor.js"></script>';
				echo '</form>';
				echo '</div>'; // <div id="newtopic">
				echo '</div>'; // <div id="menumarg">
			
				exit;

			}else{
				echo '<div id="annonce">Erreur : Veuillez-vous connecter.';
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("connexion.php")}, 4200);</script>';
				exit;
			}
		}
	}
	
	public function submitmodComment(){
		$bdd = connectSql();
		
		if(empty($_SESSION['pseudo'])){ // On vérifie que l'utilisateur est connecté
			echo '<div id="annonce">Erreur : Veuillez-vous connecter.';
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("connexion.php")}, 4200);</script>';
			exit;
		}
			
		// Je vérifie que le dernier message poster par l'utilisateur ne dépasse pas 2 minutes
		$timeneeded = $_SESSION['lastmsg']+02;
		
		if($timeneeded <= date('ymdhi') || $_SESSION['level'] == '0'){
			
			$affichecomment = $bdd->prepare('SELECT * FROM topic_comment WHERE id = :commentid');
			$sql = $affichecomment->execute(array('commentid' => $_GET['id']));
			while($sql = $affichecomment->fetch()){
				$topic_id = $sql['topic_id'];
				$auteur = $sql['auteur'];
			}
			$affichecomment->closeCursor();

			// On aura besoin de récupérer l'id de la sous_catégorie pour pouvoir envoyez une redirection sur le topic ou se trouve le commentaire.
			$reqidscat = $bdd->prepare('SELECT id_s_cat FROM sous_forum_topic WHERE id = :id');
			$sqlidscat = $reqidscat->execute(array('id' => $topic_id));

			while($sqlidscat = $reqidscat->fetch()){
				$id_s_cat = $sqlidscat['id_s_cat'];
			}
			$reqidscat->closeCursor();
			
			if($auteur !== $_SESSION['pseudo']){
				echo '<div id="annonce">Erreur : Vous ne disposez pas des droit de modification sur ce commentaire.<br/>
						Vous allez être redirigez, veuillez patientez ...<div>';
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("forum.php")}, 4200);</script>';
				exit;
			}
			
			// On vérifie que le contenu n'est pas vide..
			if(!empty($_POST['contenu'])){
				$contenuxhtml = $_POST['contenu'];
			}else{ // Sinon le contenu est vide
				echo '<div id="annonce">Erreur : Le commentaire est vide...</div>';
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("'.$_SERVER["HTTP_REFERER"].'")}, 3200);</script>';
				exit;
			}
			
			// on enregistre l'article sur la bases de données dans brouillon ici
			$req = $bdd->prepare('UPDATE topic_comment SET comment = :contenu, date = NOW() WHERE id = :id');

			$req->execute(array(
				'contenu' => $contenuxhtml,
				'id' => $_GET['id']
			));
			$req->closeCursor();
			// Je déclare l'heure actuel dans la variable session "lastmsg" comme étant l'heure du dernier message poster
			$_SESSION['lastmsg'] = date('ymdhi');
			
			echo '<div id="borderbgnomarg"><div id="bordercenter">';
			echo '<div id="center"><p>Commentaire modifier avec succés.</p>';
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("forum.php?id='.$id_s_cat.'&topic='.$topic_id.'")}, 2900);</script>';
			echo '</div></div></div>';
			exit;
		}else{
			echo '<div id="annonce">Anti-spam : Vous avez poster un commentaire ou un sujet il y à moins de 2 minutes.<br/>Veuillez patienter ...<br/></div>';
			$_SESSION['redo'] = $_POST['contenu'];
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("forum.php?id='.$id_s_cat.'&topic='.$_SERVER["HTTP_REFERER"].'")}, 3900);</script>';
			exit;
		}
	}
	
	public function deleteComment(){
		$bdd = connectSql();
		
		if(empty($_SESSION['pseudo'])){
			echo '<div id="annonce">Erreur : Veuillez-vous connecter.';
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("connexion.php")}, 4200);</script>';
			exit;
		}
		
		// on récupère le topic_id pour rediriger l'utilisateur une fois l'opération terminer
		$reqtop = $bdd->prepare('SELECT topic_id
		FROM topic_comment 
		WHERE id = :getid');
		$sqltop = $reqtop->execute(array('getid' => $_GET['id']));
		while($sqltop = $reqtop->fetch()){
			$topicidofcomment = $sqltop['topic_id'];
		}
		$reqtop->closeCursor();
		
		// On aura besoin de récupérer l'id de la sous_catégorie pour pouvoir envoyez une redirection sur le topic ou se trouve le commentaire.
		$reqidscat = $bdd->prepare('SELECT id_s_cat FROM sous_forum_topic WHERE id = :id');
		$sqlidscat = $reqidscat->execute(array('id' => $topicidofcomment));

		while($sqlidscat = $reqidscat->fetch()){
			$id_s_cat = $sqlidscat['id_s_cat'];
		}
		$reqidscat->closeCursor();

		// J'affiche une érreur si l'id est vide
		if(empty($topicidofcomment)){
			echo '<div id="annonce">Erreur : L\'id = '.$_GET['id'].' n\'existe pas.<br/>';
			echo '<a href="forum.php">Retour ...</a></div>';
			exit;
		}
		
		$affichecomment = $bdd->prepare('SELECT * FROM topic_comment WHERE id = :id'); // On récupere les commentaires
		$sqlcomment = $affichecomment->execute(array('id' => $_GET['id']));
		while($sqlcomment = $affichecomment->fetch()){
			$auteur = $sqlcomment['auteur'];
		}
		$affichecomment->closeCursor();
		
		if($_SESSION['pseudo'] == $auteur || $_SESSION['level'] == 0){
			if(empty($_GET['a']) || $_GET['a'] !== 'y'){
				echo "<p id='annonce'>Voulez-vous réellement supprimer ce commentaire ?<br/><br/>";
				echo '<a href="forum.php?id='.$_GET['id'].'&topic=delete_comment&a=y">Oui</a> |-| <a href="forum.php">Non</a></p>';
				exit;
			}else{
				
				try{
					// set the PDO error mode to exception
					$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					// sql to delete a record
					$sql3 = $bdd->prepare("DELETE FROM topic_comment WHERE id= ?");
					$sql3->execute(array($_GET['id']));
					$sql3->closeCursor();
					echo "<br/><p id='annonce'>Le commentaire à bien était suprimmer.<br/>Vous allez être rediriger...";
				}
						
				catch(PDOException $e){
					echo $sql3 . "<br />" . $e->getMessage();
				}
				$sql3->closeCursor();
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("forum.php?id='.$id_s_cat.'&topic='.$topicidofcomment.'")}, 2900);</script>';
				echo "</p>";
				exit;
			}
		}else{
			echo '<div id="annonce">Erreur : impossible d\'accéder à cette page</div>';
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("forum.php")}, 2800);</script>';
			exit;
		}
	}
}

?>