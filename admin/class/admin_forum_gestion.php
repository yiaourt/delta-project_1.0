<?php class forum_gestion{
	
	// // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 
	// La page d'accueil, ci-dessous
	public function afficheForum(){
		$bdd = connectSql();
		
		$req = $bdd->query('SELECT * FROM forum ORDER BY ordre ASC');
		$count_sql=0;
		
		while($sql_forum = $req->fetch()){
			$count_sql++;
			
			echo '<br/>';
			echo '<div id="menumarg">';
			echo '<u>catégorie :</u><hr width=\"100%\" size=\"1\" />';
			echo '<div id="center"><u>id :</u> '.$sql_forum['id'].' <u>Nom :</u> '.$sql_forum['titre'].' <u>Ordre :</u> '.$sql_forum['ordre'].'</div>
				  <hr width=\"100%\" size=\"1\" />';
			echo '<div id="center"><a href=panel_admin.php?menu=topic&mod=mod_forum&id='.$sql_forum['id'].'>Modifier le nom</a>
					| <a href=panel_admin.php?menu=topic&mod=mod_forum_order&id='.$sql_forum['id'].'>Modifier l\'ordre</a>
					| <a href=panel_admin.php?menu=topic&mod=delete_forum&id='.$sql_forum['id'].'>Supprimer</a></div>';
			echo '<div id="bordernomarg"></div>';
			
			
			$req2 = $bdd->prepare('SELECT * FROM sous_forum WHERE id_cat=? ORDER BY ordre ASC');
			$sql_sous_forum = $req2->execute(array($sql_forum['id']));
			echo '<u>Sous-catégorie(s) :</u><hr width=\"100%\" size=\"1\" />';
			while($sql_sous_forum = $req2->fetch()){
				if(!empty($sql_sous_forum['titre'])){
					echo '<hr width=\"100%\" size=\"1\" /><div><u>id : </u>'.$sql_sous_forum['id'].' -> <u>'.$sql_sous_forum['titre'].'</u></div>
					<div><u>Ordre :</u> '.$sql_sous_forum['ordre'].'</div>
					<p>description : '.$sql_sous_forum['description'].'</p><li>
					<div id="flex1border"><a href=panel_admin.php?menu=topic&mod=mod_sous_forum&id='.$sql_sous_forum['id'].'>Modifier le titre</a></div>
					<div id="flex2border"><a href="panel_admin.php?menu=topic&mod=mod_description&id='.$sql_sous_forum['id'].'">Ajouter/Modifier la description</a></div> 
					<div id="flex3border"><a href="panel_admin.php?menu=topic&mod=mod_order_sf&id='.$sql_sous_forum['id'].'">Modifier l\'ordre</a></div>
					<div id="flex4border"><a href=panel_admin.php?menu=topic&mod=delete_sous_forum&id='.$sql_sous_forum['id'].'>Supprimer</a></div></li>
					<hr width=\"100%\" size=\"1\" />';
				}
			}
			$sql_sous_forum = $req2->closeCursor();
			echo '<hr width=\"100%\" size=\"1\" />';
			echo '<div id="center"><form action="panel_admin.php?menu=topic&mod=forum_new&id_cat='.$sql_forum['id'].'" method="post">';
			echo '<div>Ajouter une sous-catégorie : <input type="text" name="new_sous_forum" />';
			echo '<input type="submit" name="add_new_forum" value="Ajouter"/></form>';
			echo '</div></div>';
			echo '</div>'; // <div id="menumarg">
			
		}
		$sql_forum = $req->closeCursor();
		if($count_sql == 0){
			echo '<div id="menumarg">';
			echo '<div id="bordernomarg"></div>';
			echo 'Aucun forum présent, vous pouvez créer une catégorie ci-dessus.';
			echo '<div id="border"></div>';
		}
	}
	
	// // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 
	// La page qui créer une nouvelle 'catégorie' ou 'sous-catégorie' sur le forum, ci-dessous
	public function newForum(){
		$bdd = connectSql();
		echo '<br/>';
		echo '</div>'; // <div id="menumarg">
		echo '<div id="menumarg">';
		echo '<div id="bordernomarg"></div>';
		echo '<div id="border"></div>';
		
		if(!empty($_POST['new_forum'])){
			
			$reqcountorder = $bdd->query('SELECT ordre FROM forum ORDER BY ordre ASC');
			
			while($sqlcountorder = $reqcountorder->fetch()){
				$ordercount = $sqlcountorder['ordre'];
			}
			
			if(empty($ordercount)){
				$ordercount=0;
			}else{
				$ordercount++;
			}
			
			$reqnewfo = $bdd->prepare('INSERT INTO forum (titre, ordre) VALUES (:titre, :ordre)');
			$reqnewfo->execute(array(
				'titre' => $_POST['new_forum'],
				'ordre' => $ordercount
			));
			echo '<div id="borderbgnomarg"><div id="bordercenter">';
			echo '<div id="center"><p>La catégorie '.$_POST['new_forum'].' à bien était créer.</p>';
			echo '<a href="panel_admin.php?menu=topic&mod=forum">Retour ...</a>';
			echo '</div></div></div>';
			$reqnewfo->closeCursor();
			exit;
		}
		
		elseif(!empty($_POST['new_sous_forum']) && !empty($_GET['id_cat'])){
			
			$reqcountorder = $bdd->prepare('SELECT ordre FROM sous_forum WHERE id_cat = :id_cat ORDER BY ordre ASC');
			$reqcountorder->execute(array('id_cat' => $_GET['id_cat']));
			
			while($sqlcountorder = $reqcountorder->fetch()){
				$ordercount = $sqlcountorder['ordre'];
			}
			
			if(empty($ordercount)){
				$ordercount=0;
			}else{
				$ordercount++;
			}
			
			$reqnewsfo = $bdd->prepare('INSERT INTO sous_forum (titre, id_cat, description, ordre) VALUES (:titre, :id_cat, :descri, :ordre)');
			$reqnewsfo->execute(array(
				'titre' => $_POST['new_sous_forum'],
				'id_cat' => $_GET['id_cat'],
				'descri' => '',
				'ordre' => $ordercount
			));
			echo '<div id="borderbgnomarg"><div id="bordercenter">';
			echo '<div id="center"><p>La sous-catégorie '.$_POST['new_sous_forum'].' à bien était créer.</p>';
			echo '<a href="panel_admin.php?menu=topic&mod=forum">Retour ...</a>';
			echo '</div></div></div>';
			$reqnewsfo->closeCursor();
			exit;
		}else{
			echo '<div id="annonce">Erreur : Vous devez définir un nom pour créer un nouveau forum.';
			echo '<br/><a href="panel_admin.php?menu=topic&mod=forum">Retour ...</a></div>';
		}
		echo '</div>'; // <div id="menumarg">
	}
	
	// // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 
	// La fonctions qui supprimera une catégorie ainsi que toutes ces sous-catégorie sujets et commentaires ...
	public function deleteForum(){
		$bdd = connectSql();
		if(empty($_GET['a']) || $_GET['a'] !== 'y'){
			echo "<p id='annonce'>Voulez-vous réellement supprimer la catégorie id = ". $_GET['id'] ." <br/>toutes ces sous-catégories, ainsi que les sujets et commentaires ?<br/><br/>";
			echo '<a href="panel_admin.php?menu=topic&mod=delete_forum&id='.$_GET['id'].'&a=y">Oui</a> |-| <a href="panel_admin.php?menu=topic&mod=forum">Non</a></p>';
		
		}elseif(!empty($_GET['a']) && $_GET['a'] == 'y'){
			
			// ///////////////////////////////////////////////////////////////////////////////////
			// On commence par Supprimmer le forum
			try{
				// set the PDO error mode to exception
				$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				// sql to delete a record
				$sqldelf = $bdd->prepare("DELETE FROM forum WHERE id= ?");
				// use exec() because no results are returned
				$sqldelf->execute(array($_GET['id']));
				$sqldelf->closeCursor();
				
				
				
				echo "<br/><p id='information'>La catégorie id = ". $_GET['id']." à bien été suprimmer.";
				echo "<br /> <a href='panel_admin.php?menu=topic&mod=forum'>Rafraichir la page</a></p>";
			}
					
			catch(PDOException $e){
				echo $sqldelf . "<br />" . $e->getMessage();
			}
			$sqldelf->closeCursor();
			
			// ///////////////////////////////////////////////////////////////////////////////////
			// On prend soin de récuperer l(es) id(s) des sous-forums dans un tableau avant de les supprimer
			$reqidsf = $bdd->prepare('SELECT id FROM sous_forum WHERE id_cat= ?');
			$reqidsf->execute(array($_GET['id']));
			
			$countidscat = 0;
			while($sqlidsf = $reqidsf->fetch()){
				// On vérifie que le sous forum existe sur la catégorie
				if(!empty($sqlidsf['id'])){
					// On récupere !
					$id_s_cat[$countidscat] = $sqlidsf['id'];
					// On supprime !
					try{
						// set the PDO error mode to exception
						$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						// sql to delete a record
						$sqldelsf = $bdd->prepare("DELETE FROM sous_forum WHERE id_cat= ?");
						// use exec() because no results are returned
						$sqldelsf->execute(array($_GET['id']));	
						$sqldelsf->closeCursor();
						
						
						
						echo "<br/><p id='information'>La sous-catégorie id = ". $sqlidsf['id']." à bien été suprimmer.";
						echo "<br /> <a href='panel_admin.php?menu=topic&mod=forum'>Rafraichir la page</a></p>";
					}
							
					catch(PDOException $e){
						echo $sqldelsf . "<br />" . $e->getMessage();
					}
					$sqldelsf->closeCursor();
					// On incrémente !
					$countidscat++;
				}
			}
			$reqidsf->closeCursor();
			
			///////////////////////////////////////////////////////////////////////////////////
			// On créer ensuite une boucle qui va supprimmer les topics en prenant soin de récuperer le(s) topic_id
			// Par la même occasion ! On supprime aussi le(s) commentaires dans une boucle juste avant d'incrémenter le topicid.
			$counttopicid=0;
			
			// On vérifie que id_s_cat existe...
			if(!empty($id_s_cat)){
				// On fait tourner la boucle tant qu'il y à de 'id_s_cat'
				foreach($id_s_cat as $id_s_cat_del){
					
					$reqidtopic = $bdd->prepare('SELECT id FROM sous_forum_topic WHERE id_s_cat= ?'); 
					$reqidtopic->execute(array($id_s_cat_del));
					
					while($sqlidtopic = $reqidtopic->fetch()){
						// On récupere !
						$id_topic[$counttopicid] = $sqlidtopic['id'];
						// On supprime Le(s) topic(s) !
						try{
							// set the PDO error mode to exception
							$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							// sql to delete a record
							$sqldelsft = $bdd->prepare("DELETE FROM sous_forum_topic WHERE id_s_cat= ?");
							// use exec() because no results are returned
							$sqldelsft->execute(array($id_s_cat_del));	
							$sqldelsft->closeCursor();
							
							
							
							echo "<br/><p id='information'>Le topic id = ".$id_topic[$counttopicid]." à bien été suprimmer.";
							echo "<br /> <a href='panel_admin.php?menu=topic&mod=forum'>Rafraichir la page</a></p>";
						}
						catch(PDOException $e){
							echo $sqldelsft . "<br />" . $e->getMessage();
						}
						$sqldelsft->closeCursor();
						
						// On supprime Le(s) commentaire(s) !
						try{
							// set the PDO error mode to exception
							$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							// sql to delete a record
							$sqldelcom = $bdd->prepare("DELETE FROM topic_comment WHERE topic_id= ?");
							// use exec() because no results are returned
							$sqldelcom->execute(array($id_topic[$counttopicid]));	
							$sqldelcom->closeCursor();
							
							
							
							echo "<br/><p id='information'>Le(s) commentaire(s) topic_id = ". $id_topic[$counttopicid] ." à bien été suprimmer.";
							echo "<br /> <a href='panel_admin.php?menu=topic&mod=forum'>Rafraichir la page</a></p>";
						}
						catch(PDOException $e){
							echo $sqldelcom . "<br />" . $e->getMessage();
						}
						$sqldelcom->closeCursor();
						// On incrémente !
						$counttopicid++;
					}
					$reqidtopic->closeCursor();
					
				}
			}
		}else{
			echo '<div id="annonce">Erreur : la catégorie id = '.$_GET['id'].' n\'existe pas.</div>';
			echo '<a href="panel_admin.php?menu=topic&mod=home">Retour ...</a>';
		}
	}
	
	// // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 
	// La page qui supprimme une sous-catégorie ainsi que ces sujets et commentaires...
	public function deleteSousForum(){
		$bdd = connectSql();
		if(empty($_GET['a']) || $_GET['a'] !== 'y'){
			echo "<p id='annonce'>Voulez-vous réellement supprimer la sous-catégorie id = ". $_GET['id'] ."<br/>ainsi que ces sujets et commentaires ?<br/>";
			echo '<a href="panel_admin.php?menu=topic&mod=delete_sous_forum&id='.$_GET['id'].'&a=y">Oui</a> |-| <a href="panel_admin.php?menu=topic&mod=forum">Non</a><p/>';
		}
		elseif(!empty($_GET['a']) && $_GET['a'] == 'y'){
			
			// ///////////////////////////////////////////////////////////////////////////////////
			// On supprime d'abord la sous-catégorie!
			try{
				// set the PDO error mode to exception
				$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				// sql to delete a record
				$sqldelsf = $bdd->prepare("DELETE FROM sous_forum WHERE id= ?");
				// use exec() because no results are returned
				$sqldelsf->execute(array($_GET['id']));	
				$sqldelsf->closeCursor();
				
				
				
				echo "<br/><p id='information'>La sous-catégorie id = ". $_GET['id']." à bien été suprimmer.";
				echo "<br /> <a href='panel_admin.php?menu=topic&mod=forum'>Rafraichir la page</a></p>";
			}
					
			catch(PDOException $e){
				echo $sqldelsf . "<br />" . $e->getMessage();
			}
			$sqldelsf->closeCursor();
			
			///////////////////////////////////////////////////////////////////////////////////
			// On créer ensuite une boucle qui va supprimmer les topics en prenant soin de récuperer le(s) topic_id
			// Par la même occasion ! On supprime aussi le(s) commentaires dans une boucle juste avant d'incrémenter le topicid.
			$counttopicid=0;
			// 
			$id_s_cat[0] = $_GET['id'];
			// On fait tourner la boucle tant qu'il y à de 'id_s_cat'
			foreach($id_s_cat as $id_s_cat_del){
				
				$reqidtopic = $bdd->prepare('SELECT id FROM sous_forum_topic WHERE id_s_cat= ?'); 
				$reqidtopic->execute(array($id_s_cat_del));
				
				while($sqlidtopic = $reqidtopic->fetch()){
					// On récupere !
					$id_topic[$counttopicid] = $sqlidtopic['id'];
					// On supprime Le(s) topic(s) !
					try{
						// set the PDO error mode to exception
						$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						// sql to delete a record
						$sqldelsft = $bdd->prepare("DELETE FROM sous_forum_topic WHERE id_s_cat= ?");
						// use exec() because no results are returned
						$sqldelsft->execute(array($id_s_cat_del));	
						$sqldelsft->closeCursor();
						
						
						
						echo "<br/><p id='information'>Le topic id = ".$id_topic[$counttopicid]." à bien été suprimmer.";
						echo "<br /> <a href='panel_admin.php?menu=topic&mod=forum'>Rafraichir la page</a></p>";
					}
					catch(PDOException $e){
						echo $sqldelsft . "<br />" . $e->getMessage();
					}
					$sqldelsft->closeCursor();
					
					// On supprime Le(s) commentaire(s) !
					try{
						// set the PDO error mode to exception
						$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						// sql to delete a record
						$sqldelcom = $bdd->prepare("DELETE FROM topic_comment WHERE topic_id= ?");
						// use exec() because no results are returned
						$sqldelcom->execute(array($id_topic[$counttopicid]));	
						$sqldelcom->closeCursor();
						
						
						
						echo "<br/><p id='information'>Le(s) commentaire(s) topic_id = ". $id_topic[$counttopicid] ." à bien été suprimmer.";
						echo "<br /> <a href='panel_admin.php?menu=topic&mod=forum'>Rafraichir la page</a></p>";
					}
					catch(PDOException $e){
						echo $sqldelcom . "<br />" . $e->getMessage();
					}
					$sqldelcom->closeCursor();
					// On incrémente !
					$counttopicid++;
					
				} // On ferme la boucle.
				$reqidtopic->closeCursor();
			}
		}
	}
		
	// // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 
	// La page qui modifie une catégorie.
	public function modForum(){
		$bdd = connectSql();
		if(!empty($_GET['id'])){
		
			if(empty($_POST['mod_forum'])){
				echo "<br/><div id='annonce'>Vous allez modifier la catégorie id = ".$_GET['id']."<br/><br/>";
				echo '<form action="panel_admin.php?menu=topic&mod=mod_forum&id='.$_GET['id'].'" method="post">';
				echo 'Nouveau nom pour la catégorie : <input type="text" name="mod_forum" />';
				echo '<input type="submit" name="mod_forum_submit" value="Modifier"/></form>
					<br/><a href="panel_admin.php?menu=topic&mod=forum">Retour ...</a></div>';
			}
			elseif(!empty($_POST['mod_forum'])){
				
				$reqmodfo = $bdd->prepare('UPDATE forum SET titre = :titre WHERE id = :id');
				$reqmodfo->execute(array(
					'titre' => $_POST['mod_forum'],
					'id' => $_GET['id']
				));
				echo '<div id="borderbgnomarg"><div id="bordercenter">';
				echo '<div id="center"><p>Catégorie modifier avec succés.</p>';
				echo '<a href="panel_admin.php?menu=topic&mod=forum">Retour ...</a>';
				echo '</div></div></div>';
				$reqmodfo->closeCursor();
				exit;
			}
		}
	}
	
	// // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 
	// La page qui modifie l'ordre d'une catégorie.
	public function modorderForum(){
		$bdd = connectSql();
		if(!empty($_GET['id'])){
		
			if(empty($_POST['mod_forum_order'])){
				echo "<br/><div id='annonce'>Vous allez modifier l'ordre de la catégorie id = ".$_GET['id']."<br/><br/>";
				echo '<form action="panel_admin.php?menu=topic&mod=mod_forum_order&id='.$_GET['id'].'" method="post">';
				echo 'Modifier l\'ordre : <input type="text" name="mod_forum_order" />';
				echo '<input type="submit" name="mod_forum_submit" value="Modifier"/></form>
					<br/><a href="panel_admin.php?menu=topic&mod=forum">Retour ...</a></div>';
			}
			elseif(!empty($_POST['mod_forum_order'])){
				
				$reqmodorderfo = $bdd->prepare('UPDATE forum SET ordre = :ordre WHERE id = :id');
				$reqmodorderfo->execute(array(
					'ordre' => $_POST['mod_forum_order'],
					'id' => $_GET['id']
				));
				echo '<div id="borderbgnomarg"><div id="bordercenter">';
				echo '<div id="center"><p>Catégorie modifier avec succés.</p>';
				echo '<a href="panel_admin.php?menu=topic&mod=forum">Retour ...</a>';
				echo '</div></div></div>';
				$reqmodorderfo->closeCursor();
				exit;
			}
		}
	}
	
	// // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 
	// La page qui modifie l'ordre d'une sous-catégorie.
	public function modordersf(){
		$bdd = connectSql();
		if(!empty($_GET['id'])){
		
			if(empty($_POST['mod_forum_order'])){
				echo "<br/><div id='annonce'>Vous allez modifier l'ordre de la sous-catégorie id = ".$_GET['id']."<br/><br/>";
				echo '<form action="panel_admin.php?menu=topic&mod=mod_order_sf&id='.$_GET['id'].'" method="post">';
				echo 'Modifier l\'ordre : <input type="text" name="mod_forum_order" />';
				echo '<input type="submit" name="mod_forum_submit" value="Modifier"/></form>
					<br/><a href="panel_admin.php?menu=topic&mod=forum">Retour ...</a></div>';
			}
			elseif(!empty($_POST['mod_forum_order'])){
				
				$reqmodordersfo = $bdd->prepare('UPDATE sous_forum SET ordre = :ordre WHERE id = :id');
				$reqmodordersfo->execute(array(
					'ordre' => $_POST['mod_forum_order'],
					'id' => $_GET['id']
				));
				echo '<div id="borderbgnomarg"><div id="bordercenter">';
				echo '<div id="center"><p>Sous-catégorie modifier avec succés.</p>';
				echo '<a href="panel_admin.php?menu=topic&mod=forum">Retour ...</a>';
				echo '</div></div></div>';
				$reqmodordersfo->closeCursor();
				exit;
			}
		}
	}
	
	// // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 
	// La page qui modifie une sous-catégorie.
	public function modsousForum(){
		$bdd = connectSql();
		if(!empty($_GET['id'])){
			if(empty($_POST['mod_sous_forum'])){
				echo "<br/><div id='annonce'>Vous allez modifier la sous-catégorie id = ".$_GET['id']."<br/><br/>";
				echo '<form action="panel_admin.php?menu=topic&mod=mod_sous_forum&id='.$_GET['id'].'" method="post">';
				echo 'Nouveau nom pour la catégorie : <input type="text" name="mod_sous_forum" />';
				echo '<input type="submit" name="mod_sous_forum_submit" value="Modifier"/></form>
					<br/><a href="panel_admin.php?menu=topic&mod=forum">Retour ...</a></div>';
			}
			elseif(!empty($_POST['mod_sous_forum'])){
				
				$reqmodsfo = $bdd->prepare('UPDATE sous_forum SET titre = :titre WHERE id = :id');
				$reqmodsfo->execute(array(
					'titre' => $_POST['mod_sous_forum'],
					'id' => $_GET['id']
				));
				echo '<div id="borderbgnomarg"><div id="bordercenter">';
				echo '<div id="center"><p>Sous-catégorie modifier avec succés.</p>';
				echo '<a href="panel_admin.php?menu=topic&mod=forum">Retour ...</a>';
				echo '</div></div></div>';
				$reqmodsfo->closeCursor();
				exit;
			}
		}
	}
	
	// // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 
	// La page qui modifie la description d'une sous-catégorie.
	public function modDescription(){
		$bdd = connectSql();
		if(!empty($_GET['id'])){
			if(empty($_POST['mod_description'])){
				echo "<br/><div id='annonce'>Veuillez ajouter une description à la sous-catégorie id = ".$_GET['id']."<br/><br/>";
				echo '<form action="panel_admin.php?menu=topic&mod=mod_description&id='.$_GET['id'].'" method="post">';
				echo 'Description : <input class="textinput" type="text" name="mod_description" />';
				echo '<input type="submit" name="mod_description_submit" value="envoyer"/></form>
					<br/><a href="panel_admin.php?menu=topic&mod=forum">Retour ...</a></div>';
			}
			elseif(!empty($_POST['mod_description'])){
			
				$reqmoddescfo = $bdd->prepare('UPDATE sous_forum SET description = :descri WHERE id = :id');
				$reqmoddescfo->execute(array(
					'descri' => $_POST['mod_description'],
					'id' => $_GET['id']
				));
				echo '<div id="borderbgnomarg"><div id="bordercenter">';
				echo '<div id="center"><p>Sous-catégorie modifier avec succés.</p>';
				echo '<a href="panel_admin.php?menu=topic&mod=forum">Retour ...</a>';
				echo '</div></div></div>';
				$reqmoddescfo->closeCursor();
				exit;
			}
		}
	}
	
	public function afficheParamforum(){
		$bdd = connectSql();
		
		echo '<div id="menumarg">';
		echo '<u>Onglets Spéciaux Sujets:</u> <hr width=\"100%\" size=\"1\" />';
		echo 'Description : Créer/Modifie/Détruit un-dés onglets spéciaux, d\'un titre de sujet.';
		echo '<div id="bordernomarg"></div><br/>';
		// On créer un formulaire.
		echo '<form action="panel_admin.php?menu=topic&mod=forum&param=submit_oss" method="post">';
		echo 'Ajouter un nouvel O.S.S (Onglet Spécial de Sujet) : <input type="text" name="name" /> Couleur HTML (#00000) : <input type="text" name="couleur" />';
		echo ' | <input type="submit" name="add_new_oss" value="Créer"/><br/><br/></form>';
		
		if(!empty($_GET['id'])){
			echo '<div id="information">';
			
			$req_oss = $bdd->prepare('SELECT * FROM onglet_special WHERE id = :getid');
			$sql_oss = $req_oss->execute(array('getid' => $_GET['id']));
			
			while($sql_oss = $req_oss->fetch()){
				
				echo 'Modifier : <span style="color: '.$sql_oss['couleur'].';">['.$sql_oss['texte'].']</span> id : '.$sql_oss['id'];
				
				echo '<br/><br/><form action="panel_admin.php?menu=topic&mod=forum&id='.$sql_oss['id'].'&param=submit_modoss" method="post">';
				
				echo 'Nouveau nom pour l\'O.S.S : <input type="text" name="name" /> Couleur HTML (#00000) : <input type="text" name="couleur" />';
				echo ' | <input type="submit" name="add_new_oss" value="Modifier"/></form></div>';
			}
		}
		
		echo '<h3><u>Liste :</u></h3>';
		
		$count_sql = 0;
		
		$req_oss = $bdd->query('SELECT * FROM onglet_special');
		while($sql_oss = $req_oss->fetch()){
			
			echo '<p><span style="color: '.$sql_oss['couleur'].';">['.$sql_oss['texte'].']</span>  | ';
			
			if($_GET['id'] == $sql_oss['id']){
				echo '<a href="panel_admin.php?menu=topic&mod=forum&param=mod"><- Retour</a>';
			}else{
				echo '<a href="panel_admin.php?menu=topic&mod=forum&param=mod&id='.$sql_oss['id'].'">Modifier</a>';
			}
			echo ' - <a href="panel_admin.php?menu=topic&mod=forum&id='.$sql_oss['id'].'&param=delete_oss">Supprimer</a></p>';
			
			$count_sql++;
		}
		
		if($count_sql == 0){ // Si count_sql vaut toujours 0 alors c'est une érreure, aucun onglet existe.
			echo '<div id="annonce">Erreur : Aucun onglets créer.</div>';
		}
		echo '</div>'; // <div id="menumarg">
	}
	
	public function submitOSS($var1, $var2){ // Envois les données pour créer un Onglet Spécial de Sujet.
		$bdd = connectSql();
		
		$req_oss_s = $bdd->prepare('INSERT INTO onglet_special
									(texte, couleur) VALUES(:nom, :color)');
		$req_oss_s->execute(array(
			'nom' => $var1,
			'color' => $var2
		));
		$req_oss_s->closeCursor();
		
		echo '<div id="borderbgnomarg"><div id="bordercenter">';
		echo '<div id="information">OSS Enregistrer. Veuillez Patienter...</div>';
		echo '<script language="Javascript">setTimeout(function(){document.location.replace("panel_admin.php?menu=topic&mod=forum&param=mod")}, 2900);</script>';
		echo '</div></div></div>';
		exit;
	}
	
	public function submitmodOSS($var1, $var2){ // envois les données pour modifier un Onglet Spécial de Sujet.
		$bdd = connectSql();
		
		$req_moss = $bdd->prepare('UPDATE onglet_special SET
									texte = :nom, couleur = :color WHERE
									id = :getid');
		$req_moss->execute(array(
			'nom' => $var1,
			'color' => $var2,
			'getid' => $_GET['id']
		));
		$req_moss->closeCursor();
		
		echo '<div id="borderbgnomarg"><div id="bordercenter">';
		echo '<div id="information">OSS id : '.$_GET['id'].' à bien était modifier. Veuillez Patienter...</div>';
		echo '<script language="Javascript">setTimeout(function(){document.location.replace("panel_admin.php?menu=topic&mod=forum&param=mod")}, 3200);</script>';
		echo '</div></div></div>';
		exit;
	}
	
	public function deleteOSS(){ // envois les données pour modifier un Onglet Spécial de Sujet.
		$bdd = connectSql();
		
		try{
				// set the PDO error mode to exception
				$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				// sql to delete a record
				$sqldeloss = $bdd->prepare("DELETE FROM onglet_special WHERE id= ?");
				// use exec() because no results are returned
				$sqldeloss->execute(array($_GET['id']));
				$sqldeloss->closeCursor();
				
				
				
				echo '<div id="borderbgnomarg"><div id="bordercenter">';
				echo '<div id="information">OSS id : '.$_GET['id'].' <u>à bien était détruit.</u> Veuillez Patienter...</div>';
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("panel_admin.php?menu=topic&mod=forum&param=mod")}, 3900);</script>';
				echo '</div></div></div>';
				exit;
			}
					
			catch(PDOException $e){
				echo $sqldeloss . "<br />" . $e->getMessage();
			}
			$sqldeloss->closeCursor();
	}
	
	
	public function error($var1){
		echo '<div id="annonce">'.$var1.'</div>';
		echo '<script language="Javascript">setTimeout(function(){document.location.replace("'.$_SERVER['HTTP_REFFER'].'")}, 3800);</script>';
		exit;
		
	}
	
}
?>