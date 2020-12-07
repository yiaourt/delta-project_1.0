<?php class Notif{
	
	
	public function initialize_TPalert(){
		$bdd = connectSql();
		
		// Ici on va calculer la variable "$_SESSION['lastdateTP']" et la colonne "lastdat_TP" sur la base de données de l'utilisateur pour initialiser une variable session d'alertes de nouveaux sujets.
		//-------------------------------------------------------------------------------------------------------------------------------
		
		// On réinitialise la variable session.
		unset($_SESSION['lastdateTP']);
		
		//-----------------------------------------------------------------------
		$_SESSION['lastdateTP'] = array();
		// Ci dessous la création de la variable "$_SESSION['lastdateTP']"
		$reqidsf = $bdd->query('SELECT id FROM sous_forum');
		
		while($sqlidsf = $reqidsf->fetch()){
			$idsf = $sqlidsf['id'];
				
			// Je fais ici une requête pour récuperer la date du dernier message du sous-forum afin d'initialiser cette valeur à un tableau qui sera stocker dans une collone utilisateur.
			$reqlastdatesf = $bdd->prepare('SELECT date FROM sous_forum_topic WHERE id_s_cat = :sqlidsf ORDER BY date DESC');
			$sqllastdatesf = $reqlastdatesf->execute(array('sqlidsf' => $idsf));
			
			$sqllastdatesf = $reqlastdatesf->fetch();
			
			if(!empty($sqllastdatesf['date'])) { // Si il y à une date de topic..
				$idsflastdate = $sqllastdatesf['date'];
			}else{ // Si aucune date existe..
				$idsflastdate = 0;
			}
			$reqlastdatesf->closeCursor();
			
			// je réinitialise ensuite la variable $_SESSION.
			$_SESSION['lastdateTP'][$idsf] = $idsflastdate;
		}
		$reqidsf->closeCursor();
		
		// -------------
		// Je récupere ici la colonne "lastdat_TP" avec une requête sql et un coup de unserialize pour transformer le tableau sql en un tableau php
		$unserialized_ldtp = array();
		
		$requserld = $bdd->prepare('SELECT lastdat_TP FROM user WHERE id = :sessionid');
		$sqluserld = $requserld->execute(array('sessionid' => $_SESSION['id']));
		
		while($sqluserld = $requserld->fetch()) {
			$unserialized_ldtp = unserialize($sqluserld['lastdat_TP']);
		}
		$requserld->closeCursor();
		
		
		// ----
		// Je fais tourner "id_s_f" de la base de données dans une boucle qui va vérifier s'il existe des nouveaux messages.
		$reqidsffv = $bdd->query('SELECT id FROM sous_forum');
		while($sqlidsffv = $reqidsffv->fetch()) {
			
			$idsf = $sqlidsffv['id'];
			
			// Je vérifie maintenant que les "lastdat_TP" de chaques "id_sous_forum" correspondent entre les 2 tableaux.
			if(empty($unserialized_ldtp[$idsf])) {
				$_SESSION['alertidsf_'.$idsf] = 0;
				
			}elseif($unserialized_ldtp[$idsf] == 0) {
				$_SESSION['alertidsf_'.$idsf] = 0;
				
			}elseif($unserialized_ldtp[$idsf] == $_SESSION['lastdateTP'][$idsf]) {// les dates correspondent, il ni à donc pas de nouveaux sujets
				$_SESSION['alertidsf_'.$idsf] = 0;
				
			}elseif($unserialized_ldtp[$idsf] >= $_SESSION['lastdateTP'][$idsf]) {
				$_SESSION['alertidsf_'.$idsf] = 0;
				
			}else{ // les dates ne correspondent pas, il y à donc un/des nouveau(x) sujet(s) 
				$_SESSION['alertidsf_'.$idsf] = 1; // J'initialise donc ici la variable alerte qui se trouve sur forum.php devant les sous-catégories. (idsf = id sous forum)

			}
			
			// J'initialise ensuite les alertes de chaques nouveaux topics de la sous-catégorie 
			// ce qui veut dire que on cherche à déterminer quelles sont les nouveaux topics.
			$reqTPdate = $bdd->prepare('SELECT id, date FROM sous_forum_topic WHERE id_s_cat=:idsf');
			$sqlTPdate = $reqTPdate->execute(array('idsf' => $idsf));
			
			while($sqlTPdate = $reqTPdate->fetch()){	// Je fais une requête en boucle des dates et id de topic.
				
				$idtp = $sqlTPdate['id'];
				
				if($sqlTPdate['date'] <= $unserialized_ldtp[$idsf]) { // Je vérifie ensuite par la date du topic si il est plus petit ou plus grand que la date sur la bdd.
					$_SESSION['alertidsf_'.$idsf.'_idtp_'.$idtp] = 0; // plus petit ou égal ce n'est pas un nouveau topic.
				}else{
					$_SESSION['alertidsf_'.$idsf.'_idtp_'.$idtp] = 1; // plus grand, c'est un noueau topic.
				}
			}
			$reqTPdate->closeCursor();
		}
		$reqidsffv->closeCursor();
		
		// print_r($unserialized_ldtp); // Un affichage du tableau des dernieres dates celui ci pour le tableau en sql !
	}
	
	
	
	public function initialize_CMalert() {
		$bdd = connectSql();
		// Ici on va calculer la variable "$_SESSION['lastdateCM']" et la colonne "lastdat_CM" sur la base de données de l'utilisateur pour initialiser une variable
		// session d'alertes de nouveaux commentaires.
		//-------------------------------------------------------------------------------------------------------------------------------
		
		// On réinitialise la variable session.
		unset($_SESSION['lastdateCM']);
		
		// On fait la même chose qu'avec la variable session ['lastdateTP'], sauf qu'on utilise la variable session ['lastdateCM'] différement,
		// étant donner que nous utilisons cette fois ci des dates de commentaires ainsi que des ids de topics cela donne un tableau comme ceci :
		// "iddetopic => date du dérnier commentaire".
		//-----------------------------------------------------------------------
		$_SESSION['lastdateCM'] = array();
		//-----------------------------------------------------------------------
		
		$reqidtop = $bdd->query('SELECT id, date FROM sous_forum_topic');
		
		while($sqlidtop = $reqidtop->fetch()) {
			$idTP = $sqlidtop['id'];
			$dateTP = $sqlidtop['date'];
			
			$reqlastdatecom = $bdd->prepare('SELECT date FROM topic_comment WHERE topic_id = :idtp ORDER BY date DESC');
			$sqllastdatecom = $reqlastdatecom->execute(array('idtp' => $idTP));
			
			$sqllastdatecom = $reqlastdatecom->fetch();
				
			if(!empty($sqllastdatecom['date'])) { // Si il y à la date d'un commentaire
				$idtplastdate = $sqllastdatecom['date'];
			}else{ // Si aucune date existe..
				$idtplastdate = $dateTP; // Pour les commentaires on initialise la date du topic plutôt qu'un 0 afin d'être sure qu'il n'y ai pas de bug avec le premier nouveau commentaire.
			}
			$reqlastdatecom->closeCursor();
			
			// je réinitialise ensuite la variable $_SESSION pour avoir en mémoire chaques dernières dates des topics du forum.
			$_SESSION['lastdateCM'][$idTP] = $idtplastdate;
			
		} // On ferme la boucle.
		$reqidtop->closeCursor();
		
		// -------------
		// Je récupere ici la colonne "lastdat_TP" avec une requête sql et un coup de unserialize pour transformer le tableau sql en un tableau php
		$unserialized_ldcm = array();
		
		$requserld = $bdd->prepare('SELECT lastdat_CM FROM user WHERE id = :sessionid');
		$sqluserld = $requserld->execute(array('sessionid' => $_SESSION['id']));
		
		while($sqluserld = $requserld->fetch()) {
			$unserialized_ldcm = unserialize($sqluserld['lastdat_CM']);
		}
		$requserld->closeCursor();
		
		// ---
		// Je fais tourner l'id de chaques topic de la base de données dans une boucle qui va vérifier s'il existe des nouveaux commentaire.
		$reqidtpfv = $bdd->query('SELECT id, id_s_cat FROM sous_forum_topic');
		while($sqlidtpfv = $reqidtpfv->fetch()) {
			
			$idtp = $sqlidtpfv['id'];
			$idsf = $sqlidtpfv['id_s_cat'];
			
			// Je vérifie maintenant que les "lastdat_TP" de chaques "id_sous_forum" correspondent entre les 2 tableaux.
			if(empty($unserialized_ldcm[$idtp])) {
				$_SESSION['alertCM_'.$idsf] = 0;
				$_SESSION['alertCM_'.$idsf.'_idtp_'.$idtp] = 0;
			}elseif($unserialized_ldcm[$idtp] == 0) {
				$_SESSION['alertCM_'.$idsf] = 0;
				$_SESSION['alertCM_'.$idsf.'_idtp_'.$idtp] = 0;
				
			}elseif($unserialized_ldcm[$idtp] == $_SESSION['lastdateCM'][$idtp]) {// les dates correspondent, il ni à donc pas de nouveaux commentaires
				$_SESSION['alertCM_'.$idsf] = 0;
				$_SESSION['alertCM_'.$idsf.'_idtp_'.$idtp] = 0;
				
			}elseif($unserialized_ldcm[$idtp] >= $_SESSION['lastdateCM'][$idtp]) {
				$_SESSION['alertCM_'.$idsf] = 0;
				$_SESSION['alertCM_'.$idsf.'_idtp_'.$idtp] = 0;
				
			}else{ // les dates ne correspondent pas, il y à donc un/des nouveau(x) commentaire(s).
				$_SESSION['alertCM_'.$idsf] = 1;
				$_SESSION['alertCM_'.$idsf.'_idtp_'.$idtp] = 1;
			}
		}
		$reqidtpfv->closeCursor();
		
		//eprint_r($unserialized_ldcm); // Un affichage du tableau des dernieres dates celui ci pour le tableau en sql !
	}
	
	
	
	public function R0(){
		$bdd = connectSql();
		
		// On fait ici un réinitialisateur de variables pour les notifications.
		$_SESSION['lastdateTP'] = array();
		//-----------------------------------------------------------------------
		// J'initialise "id_s_f => dernière date du dernier topic" dans un tableau et une variable session pour pouvoir notifier l'utilisateur de nouveau topic par la suite.
		// Je commence par récuperer chaques "id" de chaques sous-catégories dans la base de données.
		$reqidsf = $bdd->query('SELECT id FROM sous_forum');
		
		while($sqlidsf = $reqidsf->fetch()){
			$idsf = $sqlidsf['id'];

			// Je fais ici une requête pour récuperer la date du dernier message du sous-forum afin d'initialiser cette valeur à un tableau qui sera stocker dans une collone utilisateur.
			$reqlastdatesf = $bdd->prepare('SELECT id, date FROM sous_forum_topic WHERE id_s_cat = :sqlidsf ORDER BY date DESC');
			$sqllastdatesf = $reqlastdatesf->execute(array('sqlidsf' => $idsf));
			
			$sqllastdatesf = $reqlastdatesf->fetch();
				
			if(!empty($sqllastdatesf['date'])) { // Si il y à une date de topic..
				$idsflastdate = $sqllastdatesf['date'];
			}else{ // Si aucune date existe..
				$idsflastdate = '0';
			}

			$idtp = $sqllastdatesf['id'];

			$reqlastdatesf->closeCursor();
			
			// je réinitialise ensuite la variable $_SESSION pour avoir en mémoire chaques dernières dates des topics du forum.
			$_SESSION['lastdateTP'][$idtp] = $idsflastdate;
		}
		$reqidsf->closeCursor();
		
		//-----------------------------------------------------------------------
		// On réinitialise la variable session.
		unset($_SESSION['lastdateCM']);
		
		//-----------------------------------------------------------------------
		$_SESSION['lastdateCM'] = array();
		// Je cherche maintenant à créer la variable "$_SESSION['lastdateCM']" contenant les dernières dates de chaques commentaires associer au topic du forum.
		// Je fais pour cela une requête de chaques id de topics.
		//-----------------------------------------------------------------------
		$reqidtop = $bdd->query('SELECT id, date FROM sous_forum_topic');
		
		while($sqlidtop = $reqidtop->fetch()) {
			$idTP = $sqlidtop['id'];
			$dateTP = $sqlidtop['date'];
			
			$reqlastdatecom = $bdd->prepare('SELECT date FROM topic_comment WHERE topic_id = :idtp ORDER BY date DESC');
			$sqllastdatecom = $reqlastdatecom->execute(array('idtp' => $idTP));
			
			$sqllastdatecom = $reqlastdatecom->fetch();
				
			if(!empty($sqllastdatecom['date'])) { // Si il y à la date d'un commentaire
				$idtplastdate = $sqllastdatecom['date'];
			}else{ // Si aucune date existe..
				$idtplastdate = $dateTP; // Pour les commentaires on initialise la date du topic plutôt qu'un 0 afin d'être sure qu'il n'y ai pas de bug avec le premier nouveau commentaire.
			}
			$reqlastdatecom->closeCursor();
			
			// je réinitialise ensuite la variable $_SESSION pour avoir en mémoire chaques dernières dates des topics du forum.
			$_SESSION['lastdateCM'][$idTP] = $idtplastdate;
			
		} // On ferme la boucle.
		$reqidtop->closeCursor();
		
		// J'utilise serialize() pour convertir les tableaux php en un tableau sql.
		$serializedvarTP = serialize($_SESSION['lastdateTP']);
		$serializedvarCM = serialize($_SESSION['lastdateCM']);
		
		// J'envois maintenant les tableaux dans la colonne "lastdat" de l'utilisateur.
		$requserlastdat = $bdd->prepare('UPDATE user SET lastdat_TP = :serializedvarTP, lastdat_CM = :serializedvarCM WHERE id = :sessionid');
		$sqluserlastdat = $requserlastdat->execute(array(
														'serializedvarTP' => $serializedvarTP,
														'serializedvarCM' => $serializedvarCM,
														'sessionid' => $_SESSION['id']
		));
		$requserlastdat->closeCursor();
		//-----------------------------------------------------------------------
	}
	
	
	
	public function R0_topic($idtopic) { // Cette fonction sert à réinitialiser une alerte d'un topic
		$bdd = connectSql();
		
		$reqTP = $bdd->prepare('SELECT id_s_cat, date FROM sous_forum_topic WHERE id = :idtopic');
		$sqlTP = $reqTP->execute(array('idtopic' => $idtopic));
		
		while($sqlTP = $reqTP->fetch()) {
			$idsf = $sqlTP['id_s_cat'];
			$_SESSION['lastdateTP'][$idsf] = $sqlTP['date']; // On réinitialise le tableau des dernières dates de la catégorie avec la date du topic ouvert par l'utilisateur.
		}
		$reqTP->closeCursor();
		
		// J'utilise serialize() pour convertir un tableau php en un tableau sql.
		$serializedvar = serialize($_SESSION['lastdateTP']);
		
		// J'envois maintenant le tableau dans la colonne "lastdat_TP" de l'utilisateur.
		$requserlastdat = $bdd->prepare('UPDATE user SET lastdat_TP = :serializedvar WHERE id = :sessionid');
		$sqluserlastdat = $requserlastdat->execute(array(
														'serializedvar' => $serializedvar,
														'sessionid' => $_SESSION['id']
		));
		$requserlastdat->closeCursor();
	}
	
	
	
	public function R0_comment($idtopic) { // Cette fonction sert à réinitialiser une alerte d'un topic
		$bdd = connectSql();
		
		$reqCM = $bdd->prepare('SELECT date FROM topic_comment WHERE topic_id = :idtopic ORDER BY date DESC');
		$sqlCM = $reqCM->execute(array('idtopic' => $idtopic));
		
		$sqlCM = $reqCM->fetch();
		
		$_SESSION['lastdateCM'][$idtopic] = $sqlCM['date'];
		
		$reqCM->closeCursor();
		
		// J'utilise serialize() pour convertir un tableau php en un tableau sql.
		$serializedvar = serialize($_SESSION['lastdateCM']);
		
		// J'envois maintenant le tableau dans la colonne "lastdat_CM" de l'utilisateur.
		$requserlastdatCM = $bdd->prepare('UPDATE user SET lastdat_CM = :serializedvar WHERE id = :sessionid');
		$sqluserlastdatCM = $requserlastdatCM->execute(array(
														'serializedvar' => $serializedvar,
														'sessionid' => $_SESSION['id']
		));
		$requserlastdatCM->closeCursor();
	}
}
?>