<?php 
class aff_MSG{

	public function afficheMSG(){
		$bdd = connectSql();

			$nbaffichage = 0;
			$affichemsg = $bdd->prepare('SELECT *,
			DAY(date) AS jour, MONTH(date) AS mois, YEAR(date) AS annee, HOUR(date) AS heure, MINUTE(date) AS minute, SECOND(date) AS seconde
			FROM messagerie 
			WHERE id = :msgid'); // On récupere le sujet
			$sqlmsg = $affichemsg->execute(array('msgid' => $_GET['id']));
			while($sqlmsg = $affichemsg->fetch()){
				$id = $sqlmsg['id'];
				$auteur = $sqlmsg['user_principal'];
				$contacts = $sqlmsg['user_distant'];
				$titre = $sqlmsg['objet'];
				$contenu = $sqlmsg['message'];
				$msgjour = $sqlmsg['jour'];
				$msgmois = $sqlmsg['mois'];
				$msgannee = $sqlmsg['annee'];
				$msgheure = $sqlmsg['heure'];
				$msgminute = $sqlmsg['minute'];
				$msgseconde = $sqlmsg['seconde'];
				$nbaffichage++;
			}
			$affichemsg->closeCursor();
			
			// Quelques érreures
			if(empty($auteur)){
				echo '<div id="annonce">Erreur : Le message n\'existe pas.<br/>';
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("messagerie.php?new=1")}, 3800);</script>';
				exit;
			}
			
			if($_SESSION['pseudo'] !== $auteur){
				if(preg_match('#'.$_SESSION['pseudo'].'#', $contacts)){
					$varor = 1;
				}else{
					$varor = 0;
				}
				
				if($varor == 0){
					echo '<div id="annonce">Erreur : Aucun message trouver. Veuillez contacter un administrateur..<br/>Vous allez être rediriger dans quelques secondes...<br/>';
					echo '<script language="Javascript">setTimeout(function(){document.location.replace("messagerie.php")}, 3800);</script>';
					exit;
				}
			}
			
			$imguser = $bdd->prepare('SELECT img_profil FROM user WHERE username = :auteur'); // On récupere l'image de profil de l'auteur du sujet
			$sqluser = $imguser->execute(array('auteur' => $auteur));
			while($sqluser = $imguser->fetch()){
				$imgp = $sqluser['img_profil'];
			}
			$imguser->closeCursor();
		
			echo '<div id="menumarg">';
			echo '<- <a href="messagerie.php">Retour à la Messagerie</a>';
			echo '<div id="bordernomarg"></div>';
			echo '<u>Utilisateurs dans la conversation :</u> '.$contacts.'<hr width=\"100%\" size=\"1\" />';
			echo '<div class="titlecenter"><u>Objet/Titre:</u> '.$titre.'</div>';
			
			echo '<div id="bordernomarg"></div>';
			echo '<div class="flexaffmsg">';
			echo '<div id="flex1user">'.$auteur.'<br/>'.$imgp.'</div>
				  <div id="flex6topic">#'.$nbaffichage.' | '.$msgjour.'/'.$msgmois.'/'.$msgannee.' '.$msgheure.':'.$msgminute.':'.$msgseconde.'
					<hr width=\"100%\" size=\"1\" />
				  <br/>'.$contenu.'</div>';
				  
			echo '</div>'; // <div class="flexaffmsg">';
			echo '<div id="bordernomarg"></div>';
			
			if($auteur == $_SESSION['pseudo'] || $_SESSION['level'] == '0'){
				echo '<div id=right><a href="messagerie.php?mod='.$id.'">Modifier</a> | <a href="messagerie.php?delete='.$id.'">Suprimmer</a></div>';
			}
			
			echo '</div>'; 
			
			$affichecomment = $bdd->prepare('SELECT *,
			DAY(date) AS jour, MONTH(date) AS mois, YEAR(date) AS annee, HOUR(date) AS heure, MINUTE(date) AS minute, SECOND(date) AS seconde
			FROM messagerie_comment 
			WHERE msg_id = :msgid'); // On récupere les commentaires
			$sqlcomment = $affichecomment->execute(array('msgid' => $id));
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
				echo '<div class="flexaffmsg">';
				echo '<div id="flex1user">'.$sqlcomment['auteur'].'<br/>'.$imgpc.'</div>';
				echo '<div id="flex6topic">#'.$nbaffichage.' | '.$sqlcomment['jour'].'/'.$sqlcomment['mois'].'/'.$sqlcomment['annee'].' '.$sqlcomment['heure'].':'.$sqlcomment['minute'].':'.$sqlcomment['seconde'].'
					<hr width=\"100%\" size=\"1\" /><br/>'.$sqlcomment['comment'].'</div>';
				echo '</div>';
				echo '<div id="bordernomarg"></div>';
				
				if(!empty($_SESSION['pseudo'])){
					if($sqlcomment['auteur'] == $_SESSION['pseudo'] || $_SESSION['level'] == '0'){
						echo '<div id=right><a href="messagerie.php?id='.$sqlcomment['id'].'&msg=mod_comment">Modifier</a> | <a href="messagerie.php?id='.$sqlcomment['id'].'&msg=delete_comment">Suprimmer</a></div>';
					}
				}
				
				echo '</div>'; //<div id="menumarg">
			}
			$affichemsg->closeCursor();
			
			
			echo '<br/>';
			echo '<div id="menumarg">';
			
			echo '<div id="center">Ajouter un commentaire / Répondre au message : <br/>';
			echo '<form id="formsubmit" action="messagerie.php?id='.$id.'&msg=new_comment" method="post">';
			
			echo '<div id="newtopic">';
			echo '<div id="flex1"><textarea name="comment" id="sceditor">';
			if(!empty($_SESSION['redo'])){
				echo $_SESSION['redo'];
				$_SESSION['redo'] = '';
			}else{
				$_SESSION['redo'] = '';
			}
			echo '</textarea></div>';
			echo '<div id="flex2"><br/>';
			
			echo '<element onclick="changedBBcode()" id="savebutton">Enregistrer</element></div>';
			
			// On ajoute le script qui met la barre BBcode SCeditor sur le textarea. 
			echo "<script src='inc/sceditor_SMALLscript.js'>";
			echo "</script>";
			echo '</div>'; // <div id="flex2">
			echo '</div>'; // <div id="newmsg">
			echo '</div></div>'; // <div..
			echo '</form>'; // <div id="menumarg">';
			exit;
	}
	
	public function newCOM(){
		$bdd = connectSql();
		
		$affichemsg = $bdd->prepare('SELECT id, user_principal, user_distant FROM messagerie WHERE id = :msgid'); // On récupere le sujet
		$sqlmsg = $affichemsg->execute(array('msgid' => $_GET['id']));
		while($sqlmsg = $affichemsg->fetch()){
			$id = $sqlmsg['id'];
			$auteur = $sqlmsg['user_principal'];
			$contacts = $sqlmsg['user_distant'];
		}
		$affichemsg->closeCursor();
		
		//On vérifie que le message existe
		if(empty($auteur)){
			echo '<div id="annonce">Erreur : Le message n\'existe pas.<br/>';
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("messagerie.php")}, 3800);</script>';
			exit;
		}
		
		// On vérifie que l'utilisateur participe au message
		if($_SESSION['pseudo'] !== $auteur){
			if(preg_match('#'.$_SESSION['pseudo'].'#', $contacts)){
				$varor = 1;
			}else{
				$varor = 0;
			}
			
			if($varor == 0){
				echo '<div id="annonce">Erreur : Aucun message trouver. Veuillez contacter un administrateur..<br/>Vous allez être rediriger dans quelques secondes...<br/>';
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("messagerie.php")}, 3800);</script>';
				exit;
			}
		}
		
		// Je vérifie que le dernier message poster par l'utilisateur dépasse 5 minutes
		$timeneeded = $_SESSION['lastmsg']+02;
		if($timeneeded <= date('ymdhi') || $_SESSION['level'] == '0'){
			
			// On vérifie que le commentaire n'est pas vide..
			if(!empty($_POST['comment'])){
				$contenuxhtml = $_POST['comment'];
			}else{ // Sinon le commentaire est vide
				echo '<div id="annonce">Erreur : Veuillez écrire dans l\'éditeur de texte';
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("'.$_SERVER["HTTP_REFERER"].'")}, 3200);</script>';
				exit;
			}

			//  on enregistre le commentaire sur la bases de données 
			$reqnewcom = $bdd->prepare('INSERT INTO messagerie_comment 
			(msg_id, comment, auteur, date) 
			VALUES(:msgid, :contenu, :auteur, NOW())');

			$reqnewcom->execute(array(
				'msgid' => $_GET['id'],
				'contenu' => $contenuxhtml,
				'auteur' => $_SESSION['pseudo']
			));
			$reqnewcom->closeCursor();
			
			// Je déclare l'heure actuel dans la variable session "lastmsg" comme étant l'heure du dernier message poster
			$_SESSION['lastmsg'] = date('ymdhi');
			
			echo '<div id="borderbgnomarg"><div id="bordercenter">';
			echo '<div id="center"><p>Commentaire ajouter avec succés.</p>';
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("messagerie.php?id='.$_GET['id'].'")}, 2900);</script>';
			echo '</div></div></div>';
			exit;
			
		}else{
			echo '<div id="annonce">Anti-spam : Vous avez poster un commentaire ou un sujet il y à moins de 2 minutes.<br/>Veuillez patienter avant de réessayer...<br/>';
			$_SESSION['redo'] = $_POST['comment'];
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("'.$_SERVER["HTTP_REFERER"].'")}, 2900);</script>';
			exit;
		}
	}
	
	public function modCOM($comid){
		$bdd = connectSql();
		
		// On vérifie d'abord que l'utilisateur est bien l'auteur du sujet ou un admin
		// Pour ce faire on récupere des données sur phpmyadmin
		$reqmodcom = $bdd->prepare('SELECT id, auteur, comment, msg_id FROM messagerie_comment WHERE id=:idcom');

		$sqlmodcom = $reqmodcom->execute(array('idcom' => $comid));
		
		while($sqlmodcom = $reqmodcom->fetch()){
			$id = $sqlmodcom['id'];
			$auteur = $sqlmodcom['auteur'];
			$comment = $sqlmodcom['comment'];
			$msg_id = $sqlmodcom['msg_id'];
		}
		$reqmodcom->closeCursor();
		
		// Puis on lance la condition
		if($auteur == $_SESSION['pseudo'] || $_SESSION['level'] == "0"){
			// On fait un bouton Retour boite de réception.
			echo '<div class="buttonRetourMsgri"><a href="messagerie.php">Retour à la messagerie</a></div>';
			
			echo '<div id="menumarg">';
			echo '<h2>Modifier<hr width=\"100%\" size=\"1\" /></h2>'; // On affiche le titre
			
			echo '<div id="flexmsgcontact">';
			
			echo '<div class="order1">';
			echo '<div id="borderbg">';
			
			echo '<form id="formsubmit" action="messagerie.php?id='.$id.'&msg=mod_comment&sendMOD=1" method="post">';
			
			echo '<textarea name="post_comment" id="sceditor">
			'.$comment.'
			</textarea>';
			echo '<element onclick="changedBBcode()" id="savebutton">Modifier</element></div>';
				
			// On ajoute le script qui met la barre BBcode SCeditor sur le textarea. 
			echo "<script src='inc/sceditor_NORMALscript.js'>";
			echo "</script>";
			
			echo '</form>';
			
			echo '</div>'; // <div id="borderbg">';
			echo '</div>'; // <div id="order1">';
			exit;
		}else{// L'utilisateur ici n'est pas autoriser à supprimer ce message.
			
			echo '<div id="annonce">Erreur : Vous n\'êtes pas autoriser à modifier ce message. <br/> Vous allez être rediriger ...</div>';
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("messagerie.php?id='.$msg_id.'")}, 3800);</script>';
			exit;
			
		}
	}
	
	public function sendMODcom($comid){
		$bdd = connectSql();
		
		// On vérifie que l'utilisateur est bien égal à l'auteur du commentaire ou qu'il est administrateur
		$affichemsg = $bdd->prepare('SELECT auteur, msg_id FROM messagerie_comment WHERE id = :comid'); // On récupere le sujet
		$sqlmsg = $affichemsg->execute(array('comid' => $comid));
		while($sqlmsg = $affichemsg->fetch()){
			$auteur = $sqlmsg['auteur'];
			$msg_id = $sqlmsg['msg_id'];
		}
		$affichemsg->closeCursor();
		
		//On vérifie que le message existe
		if($auteur == $_SESSION['pseudo'] || $_SESSION['level'] == '0'){
			// On envoit les post du message sur la bases de donnée avec un antispam ...
			// Je vérifie que le dernier message poster par l'utilisateur dépasse pas 2 minutes
			$timeneeded = $_SESSION['lastmsg']+2;
			if($timeneeded <= date('ymdhi') || $_SESSION['level'] == '0'){
				
				//  on enregistre l'article sur la bases de données
				$reqcombdd = $bdd->prepare('UPDATE messagerie_comment SET auteur=:auteur, comment=:comment, date=NOW() WHERE id = :comid');

				$reqcombdd->execute(array(
					'auteur' => $_SESSION['pseudo'],
					'comment' => $_POST['post_comment'],
					'comid' => $comid
				));
				$reqcombdd->closeCursor();
				// Je déclare l'heure actuel dans la variable session "lastmsg" comme étant l'heure du dernier message poster
				$_SESSION['lastmsg'] = date('ymdhi');
				
				echo '<div id="borderbgnomarg"><div id="bordercenter">';
				echo '<div id="center"><p>Commentaire modifier avec succés.<br/>';
				echo '</div></div></div>';
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("messagerie.php?id='.$msg_id.'")}, 2900);</script>';
				exit;
			}else{
				echo '<div id="annonce">Anti-spam : Vous avez poster un commentaire ou un sujet il y à moins de 2 minutes.<br/>Veuillez patienter ...<br/>';
				$_SESSION['contenumsg_retour'] = $_POST['post_comment'];
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("'.$_SERVER["HTTP_REFERER"].'")}, 3800);</script>';
				exit;
			}
		}else{
			echo '<div id="annonce">Erreur : Veuillez-contacter un administrateur.<br/>Vous allez être redirigez dans quelques secondes..</div>'; // ICI ?! l'utilisateur, on ne sait pas vraiment qu'est ce qu'il fait là...
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("messagerie.php")}, 3800);</script>';
			exit;
		}
		
	}
	
	public function deleteCOM($comid){
		$bdd = connectSql();
		
		// On vérifie d'abord que l'utilisateur est bien l'auteur du sujet ou un admin
		// Pour ce faire on récupere l'auteur de l'id du message voulant être supprimer
		$reqautcom = $bdd->prepare('SELECT auteur,msg_id FROM messagerie_comment WHERE id=:idcom');

		$sqlautcom = $reqautcom->execute(array('idcom' => $comid));
		
		while($sqlautcom = $reqautcom->fetch()){
			$auteur = $sqlautcom['auteur'];
			$msgid = $sqlautcom['msg_id'];
		}
		$reqautcom->closeCursor();
		
		if($auteur == $_SESSION['pseudo']){
			
			$reqdelcom = $bdd->prepare("UPDATE messagerie_comment SET comment=:newcom WHERE id = :idcom"); // IMPORTANT Ici les commentaires seront remplacer par "Commentaire supprimer par l'utilisateur."
			$reqdelcom->execute(array(
				'idcom' => $comid,
				'newcom' => 'Commentaire supprimer par l\'utilisateur.'
			));
			$reqdelcom->closeCursor();
			
			echo "<p id='information'>Message supprimer temporairement.<br/><a href='messagerie.php?id=".$msgid."'>Rafraichir la page</a></p>";
			exit;
			
		}elseif($_SESSION['level'] == '0'){
			
			$reqdelcom = $bdd->prepare("UPDATE messagerie_comment SET comment = :newcom WHERE id = :idcom"); // IMPORTANT Ici les commentaires seront remplacer par "Commentaire supprimer par un administrateur."
			$reqdelcom->execute(array(
				'idcom' => $comid,
				'newcom' => 'Commentaire supprimer par un administrateur.'
			));
			$reqdelcom->closeCursor();
			
			echo "<p id='information'>Message supprimer temporairement.<br/><a href='messagerie.php?id=".$msgid."'>Rafraichir la page</a></p>";
			exit;
		}else{ // L'utilisateur ici n'est pas autoriser à supprimer ce message.
			echo '<div id="annonce">Erreur : Vous n\'êtes pas autoriser à supprimer ce message.</div>';
			echo "<br /> <a href='messagerie.php'>Rafraichir la page</a></p>";
			exit;
		}
	}
	
	public function error_session(){
		echo '<div id="annonce">Erreur : Vous devez être connecter pour afficher cette page.<br/><br/> Veuillez patienter, vous allez être redirigez dans quelques secondes..</div>';
		echo '<script language="Javascript">setTimeout(function(){document.location.replace("connexion.php")}, 3800);</script>';
		exit;
		
	}
	
	public function error($var1){
		echo '<div id="annonce">'.$var1.'</div>';
		$_SESSION['contenumsg_retour'] = $_POST['post_message'];
		echo '<script language="Javascript">setTimeout(function(){document.location.replace("messagerie.php?new=1")}, 3800);</script>';
		exit;
		
	}
	
	public function errorRefer($var1){
		echo '<div id="annonce">'.$var1.'</div>';
		echo '<script language="Javascript">setTimeout(function(){document.location.replace("'.$_SERVER["HTTP_REFERER"].'")}, 3800);</script>';
		exit;
		
	}
}

?>