<?php 
class aff_sous_forum{

	public function affichesousForum(){
		$bdd = connectSql();
		
		$idsf = $_GET['id']; // Une variable qui veut dire id_sous_forum.
		
		// On récupère tous les topics du sous forum à l'id correspondante
		$reqsousforum = $bdd->prepare('SELECT * FROM sous_forum WHERE id = :id');
		$sqlsousforum = $reqsousforum->execute(array('id' => $_GET['id']));
		
		while($sqlsousforum = $reqsousforum->fetch()){
			$titreisempty = $sqlsousforum['titre'];

			// On vérifie qu'il ni à pas d'érreurs avec les topics
			if(empty($titreisempty)){
				echo '<div id="annonce">Erreur : La sous-catégorie id = '.$_GET['id'].' n\'existe pas.<br/>';
				echo '<a href="forum.php">Retour ...</a></div>';
				exit;
			}
			
			// puis on affiche le titre de la sous-catégorie ainsi que le haut du tableau d'affichage des topics.
			echo '<div id="whereisuser"><span id="WIUlink"><a href="forum.php">Forum</a></span> &#10148; <span id="WIUlink">'.$sqlsousforum['titre'].'</span> &#10148; </div>'; 	// j'affiche ou ce situe l'utilisateur dans le forum
			
			echo '<div id="bordergreenleftright">'.$sqlsousforum['titre'];// Je met le titre de la sous-catégorie
			
			echo '<label class="newtopic"><a href="forum.php?id='.$_GET['id'].'&topic=new">Créer un nouveau sujet</a></label>';
			
			echo '<div id="catforum_2">';

			echo '<li id="aff_forum"><div id="flex1">Sujets</div>';
			echo '<div id="datecatnoborder">Date de création (d/m/y)</div>';
			echo '<div id="nbmsg">Total</div></li><hr width=\"100%\" size=\"1\" />';

		}
		$reqsousforum->closeCursor();

		// On vérifie ci dessous qu'il existe des topics.
		// Puis je récupère les sujets de la sous-catégorie
		$topicexist = $bdd->prepare('SELECT id FROM sous_forum_topic WHERE id_s_cat = :getid');
		
		$topicexist->execute(array(
			'getid' => $_GET['id']
		));
		if($topicexist->fetch() == false){ // Si la requête sql ne renvois aucune données.
				
			// On affiche un message d'érreur si aucun sujet existe.
			echo '<div id="annonce">Erreur : Aucun sujets trouver, vous pouvez créer un sujet <a href="forum.php?id='.$_GET['id'].'&topic=new">ici</a></div>';
			echo '</div>'; //<div id="catforum">
			
			$topicexist->closeCursor();
			exit;
		
		}
		$topicexist->closeCursor();

		// On fait un système de pagination du nombre de sous_forum_topic
		$nombreDeMessagesParPage = 15;
		
		// On récupère le nombre total de messages
		$reqcountmsg = $bdd->prepare('SELECT COUNT(*) AS nb_messages FROM sous_forum_topic WHERE id_s_cat = :getid');
		$sqlcountmsg = $reqcountmsg->execute(array('getid' => $_GET['id']));
		while ($sqlcountmsg = $reqcountmsg->fetch()){
			$totalDesMessages = $sqlcountmsg['nb_messages'];
		}
		$reqcountmsg->closeCursor();
		
		// On calcule le nombre de pages à créer
		$nombreDePages  = ceil($totalDesMessages / $nombreDeMessagesParPage);
		
		// On affiche ensuite les messages selon la page ou l'on est
		if (isset($_GET['page']))
		{
			$page = $_GET['page']; // On récupère le numéro de la page indiqué dans l'adresse
		}
		else // La variable n'existe pas, c'est la première fois qu'on charge la page
		{
			$page = 1; // On se met sur la page 1 (par défaut)
		}
		
		// On calcule le numéro du premier message qu'on prend pour le LIMIT et faire de la pagination.
		$premierMessageAafficher = ($page -1) * $nombreDeMessagesParPage;
		
		// Puis je récupère les sujets de la sous-catégorie
		$reqtopic = $bdd->prepare('SELECT *, DAY(date) AS jour, MONTH(date) AS mois, YEAR(date) AS annee FROM sous_forum_topic WHERE id_s_cat = :getid ORDER BY date DESC LIMIT '.$premierMessageAafficher.', 15');
		
		$reqtopic->execute(array(
			'getid' => $_GET['id']
		));

		while($sqltopic = $reqtopic->fetch()){ // Puis je l'affiche dans une boucle avec des liens

			$idtp = $sqltopic['id'];
			$nbmsg = 0;
			
			// Ici, je fais une requête pour savoir combien de commentaires il y à dans le sujet
			$req4 = $bdd->prepare('SELECT * FROM topic_comment WHERE topic_id = :topicid');
			$sql4 = $req4->execute(array('topicid' => $sqltopic['id'])); // il y à au total.
			
			while($sql4 = $req4->fetch()){
				$nbmsg++;
			}
			$req4->closeCursor();
			
			$nbmsg++; // parce que il y à un topic cela fait déjà un message je rajoute donc +1 à "nbmsg".  
			
			// l'afficheur d'alerte de notifications ci-dessous.
			//-----------------------------------------
			//print_r($_SESSION['alertidsf_'.$idsf.'_idtp_'.$idtp]); // une aide pour afficher la variable d'alertes.
			
			if(empty($_SESSION['alertidsf_'.$idsf.'_idtp_'.$idtp]) && empty($_SESSION['alertCM_'.$idsf.'_idtp_'.$idtp])) { // Par défault si l'utilisateur n'est pas connecté.
				
				echo '<div id="notifalertTP"><img src="img/off_';
				if($_SESSION['style'] == 'light'){
					echo 'light';
				}elseif($_SESSION['style'] == 'carbon'){
					echo 'carbon';
				}
				echo '.png" alt="off" height="20" width="20"></img></div>';
			
			}elseif(!empty($_SESSION['alertidsf_'.$idsf.'_idtp_'.$idtp]) OR !empty($_SESSION['alertCM_'.$idsf.'_idtp_'.$idtp])) {// Si l'alerte de notification du topic = 1
				
				if($_SESSION['alertidsf_'.$idsf.'_idtp_'.$idtp] == 1 OR $_SESSION['alertCM_'.$idsf.'_idtp_'.$idtp] == 1) {
					
					echo '<div id="notifalertTP"><img src="img/on_';
					if($_SESSION['style'] == 'light'){
						echo 'light';
					}elseif($_SESSION['style'] == 'carbon'){
						echo 'carbon';
					}
					echo '.png" alt="on" height="20" width="20"></img></div>'; 
				
				}else{
					
					echo '<div id="notifalertTP"><img src="img/off_';
					if($_SESSION['style'] == 'light'){
						echo 'light';
					}elseif($_SESSION['style'] == 'carbon'){
						echo 'carbon';
					}
					echo '.png" alt="off" height="20" width="20"></img></div>';
				}
			
			}else{
				
				echo '<div id="notifalertTP"><img src="img/off_';
				if($_SESSION['style'] == 'light'){
					echo 'light';
				}elseif($_SESSION['style'] == 'carbon'){
					echo 'carbon';
				}
				echo '.png" alt="off" height="20" width="20"></img></div>';
				
			}
			//-----------------------------------------
			
			if(!empty($_SESSION['pseudo']) && $_SESSION['level'] == 0){	// On affiche le droit administrateurs de sélectionner les topics pour les supprimer ou les bouger.
				echo '<form method="post" action="forum.php?topic=forum_admin">';
				echo '<INPUT id="floatleft" type="checkbox" name="delmove[]" value="'.$sqltopic['id'].'">';
			}


			echo '<li id="aff_forum">';
			// on affiche le topic
			
			
			// en récuperant les OSS du sujet pour l'afficher dans le titre.
			$id_oss = preg_split("/[\s,]+/", $sqltopic['OSS']); // On scinde la colonne OSS du sujet pour mettre chaques valeurs dans un tableau..
			
			foreach($id_oss as $item_oss){ // tableau que l'on boucle pour connaitre l'affichage du texte selon l'id de l'OSS dans sa colonne onglet_special.
				$req_woss = $bdd->prepare('SELECT * FROM onglet_special WHERE id= :idossitem');
				$sql_woss = $req_woss->execute(array('idossitem' => $item_oss));
				while($sql_woss = $req_woss->fetch()){
					echo '<span style="color: '.$sql_woss['couleur'].';">['.$sql_woss['texte'].']</span> ';
				}
			}
			// On affiche le titre en vérifiant si il y à une notification alors on affiche une bordure rouge
			if(!empty($_SESSION['alertidsf_'.$idsf.'_idtp_'.$idtp]) OR !empty($_SESSION['alertCM_'.$idsf.'_idtp_'.$idtp])) {
				
				if($_SESSION['alertidsf_'.$idsf.'_idtp_'.$idtp] == 1 OR $_SESSION['alertCM_'.$idsf.'_idtp_'.$idtp] == 1) {
					echo '<a href="forum.php?id='.$_GET['id'].'&topic='.$sqltopic['id'].'" id="flex1" class="titreTPon">'.$sqltopic['titre'].'</a>';
				}else{
					echo '<a href="forum.php?id='.$_GET['id'].'&topic='.$sqltopic['id'].'" id="flex1" class="titreTPoff">'.$sqltopic['titre'].'</a>';
				}
				
			}else{
				echo '<a href="forum.php?id='.$_GET['id'].'&topic='.$sqltopic['id'].'" id="flex1" class="titreTPoff">'.$sqltopic['titre'].'</a>';
			}

			
			// La date de création en vérifiant si il y à une notification alors on affiche une bordure rouge
			if(!empty($_SESSION['alertidsf_'.$idsf.'_idtp_'.$idtp]) OR !empty($_SESSION['alertCM_'.$idsf.'_idtp_'.$idtp])) {
				
				if($_SESSION['alertidsf_'.$idsf.'_idtp_'.$idtp] == 1 OR $_SESSION['alertCM_'.$idsf.'_idtp_'.$idtp] == 1) {
					echo '<div id="dateTPon">'.$sqltopic['jour'].'/'.$sqltopic['mois'].'/'.$sqltopic['annee'].'</div>';
				}else{
					echo '<div id="dateTPoff">'.$sqltopic['jour'].'/'.$sqltopic['mois'].'/'.$sqltopic['annee'].'</div>';
				}
				
			}else{
				echo '<div id="dateTPoff">'.$sqltopic['jour'].'/'.$sqltopic['mois'].'/'.$sqltopic['annee'].'</div>';
			}
			
			echo '<div id="nbmsg">  | '.$nbmsg.'</div>';

			echo '</li>';
		}
		$reqtopic->closeCursor();

		// On ajoute le bouton de supression de sujets aprés la boucle
		if(!empty($_SESSION['pseudo']) && $_SESSION['level'] == 0){ 
			echo '<input type="submit" name="move" value="Move" />';
			echo '| <input type="submit" name="delete" value="Delete" />';
			echo '</form>';
		}

		// Ici je fais une boucle pour écrire les liens vers chacune des pages
			echo '<div id="center">Page : ';
			
			for ($i = 1 ; $i <= $nombreDePages ; $i++)
			{
				if(!empty($_GET['page']) && $_GET['page'] == $i || $page == $i){
					echo $i. ' | ';
				}else{
					echo '<a href="forum.php?id='.$_GET['id'].'&page=' . $i . '">' . $i . '</a> | ';
				}
			}
			echo '</div>';
			
		echo '</div>'; // On ferme le bloc <div id="catforum">
	}
}

?>