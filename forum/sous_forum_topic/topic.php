<?php 
class topic{

	public function afficheTopic(){

		if(!empty($_GET['topic'])){

			$bdd = connectSql();

			$nbaffichage = 0;

			$affichetopic = $bdd->prepare('SELECT *,
			DAY(date) AS jour, MONTH(date) AS mois, YEAR(date) AS annee, HOUR(date) AS heure, MINUTE(date) AS minute, SECOND(date) AS seconde
			FROM sous_forum_topic 
			WHERE id = :topicid'); // On récupere le sujet

			$sqltopic = $affichetopic->execute(array('topicid' => $_GET['topic']));

			while($sqltopic = $affichetopic->fetch()){
				$id = $sqltopic['id'];
				$id_s_cat = $sqltopic['id_s_cat'];
				$auteur = $sqltopic['auteur'];
				$titre = $sqltopic['titre'];
				$contenu = $sqltopic['contenu'];
				$topicjour = $sqltopic['jour'];
				$topicmois = $sqltopic['mois'];
				$topicannee = $sqltopic['annee'];
				$topicheure = $sqltopic['heure'];
				$topicminute = $sqltopic['minute'];
				$topicseconde = $sqltopic['seconde'];
				$nbaffichage++;
			}
			$affichetopic->closeCursor();
			
			if(empty($auteur)){
				echo '<div id="annonce">Erreur : Le sujet n\'existe pas.<br/>';
				echo '<a href="forum.php">Retour ...</a></div>';
				exit;
			}
			
			$imguser = $bdd->prepare('SELECT img_profil FROM user WHERE username = :auteur'); // On récupere l'image de profil de l'auteur du sujet
			$sqluser = $imguser->execute(array('auteur' => $auteur));
			while($sqluser = $imguser->fetch()) {
				$imgp = $sqluser['img_profil'];
			}
			$imguser->closeCursor();
			
			if(!isset($imgp)) { // Simple condition pour savoir si l'utilisateur existe dans la base de données.
				$imgp = '<i>l\'utilisateur n\'existe plus.</i>';
			}
				
			$whereisuser = $bdd->prepare('SELECT titre FROM sous_forum WHERE id = :id');

			$cat = $whereisuser->execute(array('id' => $id_s_cat));

			while($cat = $whereisuser->fetch()) {
				$userishere = $cat['titre']; // je récupere ici la variable qui affiche ou ce situe l'utilisateur dans le forum
			}
			$whereisuser->closeCursor();
			
			// J'affiche le topic ci-dessous :
			echo '<div id="whereisuser"><span id="WIUlink"><a href="forum.php">Forum</a></span> &#10148; <span id="WIUlink"><a href="forum.php?id='.$id_s_cat.'">'.$userishere.'</a></span> &#10148; <span id="WIUlink">'.$titre.'</span></div>';
			
			echo '<div id="menumarg">';
			
			echo '<div class="titlecenter"><u>Titre :</u> '.$titre.'</div> <br/>';
			
			echo '<div id="bordernomarg"></div>';
			
			echo '<li id="aff_forum">';
			echo '<div id="flex1user"><div id="auteur_topic">'.$auteur.'</div><br/>'.$imgp.'</div>
				  <div id="flex6topic">#'.$nbaffichage.' | '.$topicjour.'/'.$topicmois.'/'.$topicannee.' '.$topicheure.':'.$topicminute.':'.$topicseconde.'
					<hr width=\"100%\" size=\"1\" />
				  <br/>'.$contenu.'</div>';

			echo '</li>';

			echo '<div id="bordernomarg"></div>';
			
			if(!empty($_SESSION['pseudo'])){
				if($auteur == $_SESSION['pseudo'] || $_SESSION['level'] == '0'){
					echo '<div id=right><a href="forum.php?id='.$_GET['topic'].'&topic=mod">Modifier</a> | <a href="forum.php?id='.$_GET['topic'].'&topic=delete">Supprimer</a></div>';
				}
			}
			
			echo '</div>'; //<div id="menumarg">
			
			
			
			// Ci-dessous l'affichage des commentaires.
			$affichecomment = $bdd->prepare('SELECT *,
			DAY(date) AS jour, MONTH(date) AS mois, YEAR(date) AS annee, HOUR(date) AS heure, MINUTE(date) AS minute, SECOND(date) AS seconde
			FROM topic_comment 
			WHERE topic_id = :topicid'); // On récupere les commentaires
			$sqlcomment = $affichecomment->execute(array('topicid' => $id));
			while($sqlcomment = $affichecomment->fetch()){
				
				$nbaffichage++;
				$imgpc = '';
				$imgusercomment = $bdd->prepare('SELECT img_profil FROM user WHERE username = :auteur'); // On récupere l'image de profil de l'auteur du sujet
				$sqlusercomment = $imgusercomment->execute(array('auteur' => $sqlcomment['auteur']));
				
				while($sqlusercomment = $imgusercomment->fetch()){
					$imgpc = $sqlusercomment['img_profil'];
				}
				$imguser->closeCursor();
				echo '<div id="menumarg">';
				echo '<div id="bordernomarg"></div>';
				echo '<li id="aff_forum">';
				echo '<div id="flex1user">'.$sqlcomment['auteur'].'<br/><hr/>'.$imgpc.'</div>';
				echo '<div id="flex6topic">#'.$nbaffichage.' | '.$sqlcomment['jour'].'/'.$sqlcomment['mois'].'/'.$sqlcomment['annee'].' '.$sqlcomment['heure'].':'.$sqlcomment['minute'].':'.$sqlcomment['seconde'].'
					<hr width=\"100%\" size=\"1\" /><br/>'.$sqlcomment['comment'].'</div>';
				echo '</li>';
				echo '<div id="bordernomarg"></div>';
				
				if(!empty($_SESSION['pseudo'])){
					if($sqlcomment['auteur'] == $_SESSION['pseudo'] || $_SESSION['level'] == '0'){
						echo '<div id=right><a href="forum.php?id='.$sqlcomment['id'].'&topic=mod_comment">Modifier</a> | <a href="forum.php?id='.$sqlcomment['id'].'&topic=delete_comment">Supprimer</a></div>';
					}
				}
				
				echo '</div>'; //<div id="menumarg">
			}
			$affichetopic->closeCursor();
			
			
			if(!empty($_SESSION['pseudo'])){
				echo '<br/>';
				echo '<div id="menumarg"><div id="newtopic">';
				
				echo '<u>Ajouter un commentaire : </u><br/><br/>';
				echo '<form id="formsubmit" action="forum.php?id='.$id.'&topic=new_comment&submit=1" method="post">';

				echo '<div id="blacktext"><textarea name="comment" id="editor">';
				if(!empty($_SESSION['redo'])){
					echo $_SESSION['redo'];
					$_SESSION['redo'] = '';
				}else{
					$_SESSION['redo'] = '';
				}
				echo '</textarea></div>';

				echo '<br/><br/>';
				
				echo '<button id="savebutton">Enregistrer</button>';
				
				// on appel ckeditor4 pour changer l'éditeur de texte "<textarea>"
				echo '<script src="inc/create_ckeditor.js"></script>';

				echo '</div></div>'; // <div id="menumarg"> <div id="newtopic">
				echo '</form>';
				echo '</div></div>'; // <div..
				

			}else{
				echo '<br/>';
				echo '<div id="menumarg">';
				echo '<div id="center">';
				echo 'Vous devez être connecter pour pouvoir poster un commentaire, <a href="inscription.php">inscrivez-vous</a> ou <a href="connexion.php">Connectez-vous</a>';
				echo '</div></div>';
				exit;
			}
		}
	}
	
	public function newTopic(){
		//!\\ L'id récuperer ici est l'id de la sous categorie
		if(!empty($_GET['id']) && !empty($_GET['topic']) && $_GET['topic'] == 'new'){
			$bdd = connectSql();
			
			if(empty($_SESSION['pseudo'])){
				echo '<div id="annonce">Erreur : Veuillez-vous connecter.';
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("connexion.php")}, 4200);</script>';
				exit;
			}
			
			$whereisuser = $bdd->prepare('SELECT * FROM sous_forum WHERE id = :id');
			$cat = $whereisuser->execute(array('id' => $_GET['id']));
			while($cat = $whereisuser->fetch()) {
				$userishere = $cat['titre']; // j'affiche ou ce situe l'utilisateur dans le forum
			}
			$whereisuser->closeCursor();
			
			// J'affiche une érreur si la variable userishere est vide parce que l'id est érroner
			if(empty($userishere)){
				echo '<div id="annonce">Erreur : La sous-catégorie id = '.$_GET['id'].' n\'existe pas.<br/>';
				echo '<a href="forum.php">Retour ...</a></div>';
				exit;
			}
			
			echo '<div id="whereisuser"><span id="WIUlink"><a href="forum.php">Forum</a></span> / <span id="WIUlink"><a href="forum.php?id='.$_GET['id'].'">'.$userishere.'</a></span></div>';
			
			echo '<div id="menumarg">';
			echo '<div id="bordernomarg"></div>';
			echo '<div id="border"></div>';
			
			echo "<div class='underline'>Ajouter un nouveau sujet sur le forum ".$userishere." :</div>";
			
			echo '<form id="formsubmit" action="forum.php?id='.$_GET['id'].'&topic=new&submit=1" method="post">';
			
			echo '<div id="newtopic">
				  <div id="flex1"><br/>Titre : <textarea name="title" class="low">';
			echo '</textarea></div>';
			
			// // // // // // // // // // // // // / // // // / / // / // / / / / // /  / // / / / / 
			// Ici, la fonction pour mettre des OSS (Onglets de Sujet Spécial)
			echo '<div id="allborder">';
			
			echo '<u>Ajouter des balises au titre du sujet :</u> (2 Maximum)';
			echo '<p>';

			$req_osst = $bdd->query('SELECT * FROM onglet_special');
			
			while($sql_osst = $req_osst->fetch()){
				echo '<INPUT type="checkbox" name="OSSitem[]" value="'.$sql_osst['id'].'" >';
				echo '<span style="color: '.$sql_osst['couleur'].';">['.$sql_osst['texte'].']</span>  |  ';
			}
			$req_osst->closeCursor();

			// <!-- Un script qui limite le nombre de checkbox cocher pour les onglet special -->
			echo '<script src="inc/js/limit_checkbox.js"></script>';
			
			echo '</p>';
			echo '</div>';
			// // // // // // // // // // // // // / // // // / / // / // / / / / // /  / // / / / / 
			
			// On créer ensuite l'éditeur de texte
			echo '<div id="flex2"><br/>
				  <textarea name="contenu" id="editor">';
			
			if(!empty($_SESSION['redo'])){
			
				echo $_SESSION['redo'];
				$_SESSION['redo'] = '';
			
			}else{
				$_SESSION['redo'] = '';
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
		}
	}
	
	public function submitTopic(){
		$bdd = connectSql();
		
		if(empty($_SESSION['pseudo'])){
			echo '<div id="annonce">Erreur : Veuillez-vous connecter.';
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("connexion.php")}, 4200);</script>';
			exit;
		}
		
		$whereisuser = $bdd->prepare('SELECT titre FROM sous_forum WHERE id = :id');
		$cat = $whereisuser->execute(array('id' => $_GET['id']));
		while($cat = $whereisuser->fetch()){
			$userishere = $cat['titre'];
		}
		$whereisuser->closeCursor();
		
		// J'affiche une érreur si la variable userishere est vide parce que l'id est érroner
		if(empty($userishere)){
			echo '<div id="annonce">Erreur : La sous-catégorie id = '.$_GET['id'].' n\'existe pas.<br/>';
			echo '<a href="forum.php">Retour ...</a></div>';
			exit;
		}
	
		if(!empty($_POST['preview'])) {

			// On vérifie que le contenu n'est pas vide..
			if(!empty($_POST['contenu'])){
				$contenuxhtml = $_POST['contenu'];
			}else{ // Sinon le contenu est vide
				echo '<div id="annonce">Erreur : Le sujet n\'à pas était écrit...';
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("'.$_SERVER["HTTP_REFERER"].'")}, 3200);</script>';
				exit;
			}
			
			// On récupere le titre
			$title = htmlspecialchars($_POST['title']);
			// On vérifie que le titre n'est pas vide
			if(empty($title)){
				echo '<div id="annonce">Erreur : Veuillez mettre un titre...';
				$_SESSION['redo'] = $_POST['contenu'];
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("'.$_SERVER["HTTP_REFERER"].'")}, 3200);</script>';
				exit;
			}
			echo '<div id="menumarg">';
			$_SESSION['preview_home'] = $_POST['contenu'];
			$_SESSION['preview_home_title'] = $title;
			
			echo '<a href="forum.php?id='.$_GET['id'].'&topic=new">Retour ...</a>';
			echo '<div id="border"></div>';
			echo $contenuxhtml;
			echo '</div>';
			exit;
		
		}else{
			
			// Je vérifie que le dernier message poster par l'utilisateur dépasse pas 2 minutes
			$timeneeded = $_SESSION['lastmsg']+2;
			if($timeneeded <= date('ymdhi') || $_SESSION['level'] == '0'){
				
				// On vérifie que le contenu n'est pas vide..
				if(!empty($_POST['contenu'])){
					$contenuxhtml = $_POST['contenu'];
				}else{ // Sinon le contenu est vide
					echo '<div id="annonce">Erreur : Le sujet n\'à pas était écrit...';
					echo '<script language="Javascript">setTimeout(function(){document.location.replace("'.$_SERVER["HTTP_REFERER"].'")}, 3200);</script>';
					exit;
				}
				
				// On récupere le titre
				$title = htmlspecialchars($_POST['title']);
				// On vérifie que le titre n'est pas vide
				if(empty($title)){
					echo '<div id="annonce">Erreur : Veuillez mettre un titre...';
					$_SESSION['redo'] = $_POST['contenu'];
					echo '<script language="Javascript">setTimeout(function(){document.location.replace("'.$_SERVER["HTTP_REFERER"].'")}, 3200);</script>';
					exit;
				}
				
				// on récupere les OSSitem
				if(isset($_POST['OSSitem'])){
					$OSS_object = '';
					foreach($_POST['OSSitem'] as $idoss){
						$OSS_object = $OSS_object.' '.$idoss; // ' ' <- est important, cela rajoute un espace entre les id_oss
					}
				}else{
					$OSS_object = '';
				}
				
				//  on enregistre l'article sur la bases de données
				$reqsft = $bdd->prepare('INSERT INTO sous_forum_topic(id_s_cat, auteur, OSS, titre, contenu, contenu_mod, date) 
																VALUES(:idscat, :auteur, :ossobj, :title, :contenu, "", NOW())');

				$reqsft->execute(array(
					'idscat' => $_GET['id'],
					'auteur' => $_SESSION['pseudo'],
					'ossobj' => $OSS_object,
					'title' => $title,
					'contenu' => $contenuxhtml
				));
				$reqsft->closeCursor();
				
				// Je réinitialise l'antispam comme étant le dernier message poster...
				$_SESSION['lastmsg'] = date('ymdhi');
				
				echo '<div id="borderbgnomarg"><div id="bordercenter">';
				echo '<div id="center"><p>Sujet ajouter avec succés.<br/>';
				echo '</div></div></div>';
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("forum.php?id='.$_GET['id'].'")}, 2900);</script>';
				exit;
			}else{
				echo '<div id="annonce">Anti-spam : Vous avez poster un commentaire ou un sujet il y à moins de 2 minutes.<br/>Veuillez patienter ...<br/>';
				$_SESSION['redo'] = $_POST['contenu'];
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("'.$_SERVER["HTTP_REFERER"].'")}, 4200);</script>';
				exit;
			}
		}
	}
	
	public function modTopic(){
		//!\\ L'id récuperer ici est l'id du topic
		if(!empty($_GET['id']) && !empty($_GET['topic']) && $_GET['topic'] == 'mod'){
			$bdd = connectSql();
			
			if(empty($_SESSION['pseudo'])){
				echo '<div id="annonce">Erreur : Veuillez-vous connecter.';
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("connexion.php")}, 4200);</script>';
				exit;
			}
			
			$whereisuser = $bdd->prepare('SELECT * FROM sous_forum_topic WHERE id = :id');
			$sous_cat = $whereisuser->execute(array('id' => $_GET['id']));
			while($sous_cat = $whereisuser->fetch()){
				$userishere = $sous_cat['id_s_cat'];
			}
			$whereisuser->closeCursor();
			
			$whereisuser = $bdd->prepare('SELECT * FROM sous_forum WHERE id = :userishere');
			$sous_cat = $whereisuser->execute(array('userishere' => $userishere));
			while($sous_cat = $whereisuser->fetch()){
				$userishere = $sous_cat['titre']; // j'affiche ou ce situe l'utilisateur dans le forum dans une variable
			}
			$whereisuser->closeCursor();
			
			// J'affiche une érreur si la variable userishere est vide parce que l'id est érroner
			if(empty($userishere)){
				echo '<div id="annonce">Erreur : La sous-catégorie id = '.$_GET['id'].' n\'existe pas.<br/>';
				echo '<a href="forum.php">Retour ...</a></div>';
				exit;
			}
			
			$affichetopic = $bdd->prepare('SELECT * FROM sous_forum_topic WHERE id = :topicid');
			$sqltopic = $affichetopic->execute(array('topicid' => $_GET['id']));
			while($sqltopic = $affichetopic->fetch()){
				$id = $sqltopic['id'];
				$id_s_cat = $sqltopic['id_s_cat'];
				$auteur = $sqltopic['auteur'];
				$titre = $sqltopic['titre'];
				$contenu = $sqltopic['contenu'];
			}
			$affichetopic->closeCursor();
			
			if(!empty($_SESSION) && $_SESSION['pseudo'] == $auteur || $_SESSION['level'] == '0'){
				
				echo '<div id="whereisuser"><span id="WIUlink"><a href="forum.php">Forum</a></span> / <span id="WIUlink"><a href="forum.php?id='.$id_s_cat.'">'.$userishere.'</a></span></div>';
				
				echo '<div id="menumarg">';
				echo '<div id="bordernomarg"></div>';
				echo '<div id="border"></div>';
				
				echo "<div class='underline'>Modifie un sujet sur le forum ".$userishere." :</div>";
				
				echo '<form id="formsubmit" action="forum.php?id='.$_GET['id'].'&topic=mod&submit=1" method="post">';
				
				echo '<div id="newtopic">
					  <div id="flex1"><br/>Titre : <textarea name="title" class="low">';
				echo $titre;
				echo '</textarea></div>';
				
				// // // // // // // // // // // // // / // // // / / // / // / / / / // /  / // / / / / 
				// Ici, la fonction pour mettre des OSS (Onglets de Sujet Spécial)
				echo '<div id="allborder">';
				echo '<u>Ajouter des balises au titre du sujet :</u> (4 Maximum)';
				echo '<p>';
				$req_osst = $bdd->query('SELECT * FROM onglet_special');
				while($sql_osst = $req_osst->fetch()){
					echo '<INPUT type="checkbox" name="OSSitem[]" value="'.$sql_osst['id'].'">';
					echo '<span style="color: '.$sql_osst['couleur'].';">['.$sql_osst['texte'].']</span>  |  ';
				}
				$req_osst->closeCursor();
				echo '</p>';
				echo '</div>';
				// // // // // // // // // // // // // / // // // / / // / // / / / / // /  / // / / / / 
				
				echo '<div id="flex2"><br/>
					  <textarea name="contenu" id="editor">';
				if(!empty($_SESSION['redo'])){
					echo $_SESSION['redo'];
					$_SESSION['redo'] = '';
				}else{
					$_SESSION['redo'] = '';
					echo htmlspecialchars($contenu);
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

			}elseif(empty($_SESSION) || $_SESSION['pseudo'] !== $auteur){
				echo '<div id="borderbgnomarg"><div id="bordercenter">';
				echo "<div id='center'><p>Vous n'êtes pas autoriser à voir cette pâge.</p>";
				echo '<a href="index.php">Retour</a>';
				echo '</div></div></div>';
				exit;
			}
		}
	}
	
	public function submitmodTopic(){
		$bdd = connectSql();

		// On aura besoin de récupérer l'id de la sous_catégorie pour pouvoir envoyez une redirection sur le topic ou se trouve le commentaire.
		$reqidscat = $bdd->prepare('SELECT id_s_cat FROM sous_forum_topic WHERE id = :id');
		$sqlidscat = $reqidscat->execute(array('id' => $_GET['id']));

		while($sqlidscat = $reqidscat->fetch()){
			$id_s_cat = $sqlidscat['id_s_cat'];
		}
		$reqidscat->closeCursor();
		
		if(empty($_SESSION['pseudo'])){
			echo '<div id="annonce">Erreur : Veuillez-vous connecter.';
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("connexion.php")}, 4200);</script>';
			exit;
		}
		
		$whereisuser = $bdd->prepare('SELECT titre FROM sous_forum_topic WHERE id = :id');
		$cat = $whereisuser->execute(array('id' => $_GET['id']));
		while($cat = $whereisuser->fetch()){
			$userishere = $cat['titre'];
		}
		$whereisuser->closeCursor();
		
		// J'affiche une érreur si la variable userishere est vide parce que l'id est érroner
		if(empty($userishere)){
			echo '<div id="annonce">Erreur : La sous-catégorie id = '.$_GET['id'].' n\'existe pas.<br/>';
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("forum.php")}, 3200);</script>';
			exit;
		}
			
		// Je vérifie que le dernier message poster par l'utilisateur dépasse 5 minutes
		$timeneeded = $_SESSION['lastmsg']+2;
		if($timeneeded <= date('ymdhi') || $_SESSION['level'] == '0'){
			
			$contenuxhtml = $_POST['contenu'];
			
			if(empty($contenuxhtml)){
				echo '<div id="annonce">Erreur : Vous devez remplir l\'éditeur de texte.<br/></div>';
				$_SESSION['redo'] = $_POST['contenu'];
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("forum.php?id='.$id_s_cat.'&topic='.$_GET['id'].'")}, 2900);</script>';
			}
			
			$title = htmlspecialchars($_POST['title']); // On récupere le titre
			
			if(empty($title)){
				echo '<div id="annonce">Erreur : Vous devez mettre un titre.<br/></div>';
				$_SESSION['redo'] = $_POST['contenu'];
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("forum.php?id='.$id_s_cat.'&topic='.$_GET['id'].'")}, 2900);</script>';
			}
			
			// on récupere les OSSitem (Onglet special sujet)
			if(empty($_POST['OSSitem'])){
				$OSS_object = '';
			}else{
				$OSS_object = '';
				foreach($_POST['OSSitem'] as $idoss){ 
					$OSS_object = $OSS_object.' '.$idoss; // ' ' <- est important, cela rajoute un espace entre les id_oss
				}
			}
			
			//  on enregistre l'article sur la bases de données dans brouillon ici
			// Puis on écrit les données sur la base de données
			$requp = $bdd->prepare('UPDATE sous_forum_topic SET OSS = :ossobj, titre = :title, contenu = :contenu, date = NOW() WHERE id = :id');

			$requp->execute(array(
				'ossobj' => $OSS_object,
				'title' => $title,
				'contenu' => $contenuxhtml,
				'id' => $_GET['id']
			));
			$requp->closeCursor();

			// Je déclare l'heure actuel dans la variable session "lastmsg" comme étant l'heure du dernier message poster
			$_SESSION['lastmsg'] = date('ymdhi');
			
			echo '<div id="borderbgnomarg"><div id="bordercenter">';
			echo '<div id="center"><p>Sujet modifier avec succés.</p>';
			echo '</div></div></div>';
			
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("forum.php?id='.$id_s_cat.'&topic='.$_GET['id'].'")}, 2900);</script>';
			exit;
		}else{
			echo '<div id="annonce">Anti-spam : Vous avez poster un commentaire ou un sujet il y à moins de 2 minutes.<br/>Veuillez patienter ...<br/></div>';
			$_SESSION['redo'] = $_POST['contenu'];
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("forum.php?id='.$id_s_cat.'&topic='.$_GET['id'].'")}, 2900);</script>';
			exit;
		}
	}
	
	public function deleteTopic(){
		$bdd = connectSql();

		if(empty($_SESSION['pseudo'])){
			echo '<div id="annonce">Erreur : Veuillez-vous connecter.';
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("connexion.php")}, 4200);</script>';
			exit;
		}
		
		// J'affiche une érreur si l'id est vide
		if(empty($idscatoftopic)){
			echo '<div id="annonce">Erreur : L\'id = '.$_GET['id'].' n\'existe pas.<br/>';
			echo '<a href="forum.php">Retour ...</a></div>';
			exit;
		}
		
		// On aura besoin de récupérer l'id de la sous_catégorie pour pouvoir envoyez une redirection sur le topic ou se trouve le commentaire.
		$reqidscat = $bdd->prepare('SELECT id_s_cat FROM sous_forum_topic WHERE id = :id');
		$sqlidscat = $reqidscat->execute(array('id' => $_GET['id']));

		while($sqlidscat = $reqidscat->fetch()){
			$id_s_cat = $sqlidscat['id_s_cat'];
		}
		$reqidscat->closeCursor();

		// On récupere le sujet ...
		$affichetopic = $bdd->prepare('SELECT * FROM sous_forum_topic WHERE id = :topicid'); 
		$sqltopic = $affichetopic->execute(array('topicid' => $_GET['id']));
		while($sqltopic = $affichetopic->fetch()){
			$auteur = $sqltopic['auteur'];
		}
		$affichetopic->closeCursor();
		
		if($_SESSION['pseudo'] == $auteur || $_SESSION['level'] == 0){
			if(empty($_GET['a']) || $_GET['a'] !== 'y'){
				echo "<p id='annonce'>Voulez-vous réellement supprimer le sujet<br/>ainsi que tous les commentaires ?<br/><br/>";
				echo '<a href="forum.php?id='.$_GET['id'].'&topic=delete&a=y">Oui</a> |-| <a href="forum.php?id='.$id_s_cat.'&topic='.$_GET['id'].'">Non</a></p>';
			}else{
				
				// Puis je fais l'opération de supression
				try{
					// set the PDO error mode to exception
					$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					// sql to delete a record
					$sql = $bdd->prepare("DELETE FROM sous_forum_topic WHERE id= ?");
					$sql->execute(array($_GET['id']));
					$sql->closeCursor();
					$sql2 = $bdd->prepare("DELETE FROM topic_comment WHERE topic_id= ?");
					$sql2->execute(array($_GET['id']));
					$sql2->closeCursor();
					echo "<br/><p id='annonce'>Le sujet à bien était suprimmer ainsi que tous les commentaires présent.";
				}
						
				catch(PDOException $e){
					echo $sql . "<br />" . $e->getMessage();
				}
				$sql->closeCursor();
				echo "<br /> <a href='forum.php?id=".$id_s_cat."&topic=".$_GET['id']."'>Rafraichir la page</a></p>";
				exit;
			}
		}else{
			echo '<div id="annonce">Erreur : impossible d\'accéder à cette page</div>';
			echo '<a href="forum.php">Retour ...</a>';
			exit;
		}
	}
}

?>