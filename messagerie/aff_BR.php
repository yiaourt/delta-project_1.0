<?php 
class aff_BR{

	public function afficheBR(){
		$bdd = connectSql();
		
		echo '<div id="menumarg">';
		// On fait un bouton nouveau message.
		echo '<div class="buttonNewMsg"><a href="messagerie.php?new=1">Envoyer un nouveau message</a></div>';
		
		echo '<h2>Messagerie<hr width=\"100%\" size=\"1\" /></h2>'; // On affiche le titre de la boite de réception.

		// On affiche le nom des collones à l'utilisateur
		echo '<div class="flexaffnameconv">
				
				<div id="flex1" class="auteurmsg">Auteur</div>
				<div id="flex35" class="titremsg">Titre/Objet</div>
				<div id="flex2" class="userinmsg">Utilisateurs</div>
				<div class="nbcom">Total</div>
				
				</div>';
		
		// ici le formulaire de supression commence.
		echo '<form action="messagerie.php?delete=delete" method="post">';
		
		
		// ICI !!! Nous fesons une double requêtes pour avoir - les messages créer par l'utilisateur.
		//
		$reqmsgrizi = $bdd->prepare('SELECT * FROM messagerie WHERE user_principal = :sesspsd');
		$sqlmsgrizi = $reqmsgrizi->execute(array(
			'sesspsd' => $_SESSION['pseudo']
		));
		
		while($sqlmsgrizi = $reqmsgrizi->fetch()){
			// on affiche chaques messages de l'utilisateur sur la base de donnée (envoyer et reçus)

			// On récupère le nombre total de commentaires dans le message
			$nbcom = 0;
		
			$req4 = $bdd->prepare('SELECT * FROM messagerie_comment WHERE msg_id = :msgid');// Ici, je fais une requête pour savoir combien de commentaires
			$sql4 = $req4->execute(array('msgid' => $sqlmsgrizi['id']));				// il y à au total dans messagerie_comment.
			
			while($sql4 = $req4->fetch()){													
				$nbcom++;
			}
			$req4->closeCursor();
			
			$nbcom++; // parce que il y à un topic cela fait déjà un message je rajoute donc +1 à "nbmsg".
			
			// puis on affiche le message
			echo '<div class="flexaffconv">';
			
			if($_SESSION['pseudo'] == $sqlmsgrizi['user_principal'] || $_SESSION['level'] == 0){	// On affiche le droit administrateurs de sélectionner les topics pour les supprimer ou les bouger.
				echo '<INPUT type="checkbox" name="del[]" value="'.$sqlmsgrizi['id'].'">';
			}
			
			echo '
			<div id="flex1" class="auteurmsg">'.$sqlmsgrizi['user_principal'].'</div>
			<div id="flex3" class="titremsg"><a href="messagerie.php?id='.$sqlmsgrizi['id'].'">'.$sqlmsgrizi['objet'].'</a></div>
			<div id="flex2" class="userinmsg">'.$sqlmsgrizi['user_distant'].'</div>
			<div class="nbcom">'.$nbcom.'</div>
			
			</div><hr width=\"100%\" size=\"1\" />'; // <div class="flexaffconv">';
		}
		$reqmsgrizi->closeCursor();
			
		// et ICI !!! - les messages ou l'utilisateur participe au message.
		$requserd = $bdd->query('SELECT * FROM messagerie');
		
		while($sqluserd = $requserd->fetch()){
			
			$contacts = $sqluserd['user_distant'];
			
			if(preg_match('#'.$_SESSION['pseudo'].'#', $contacts)){ // On recherche le pseudonyme de l'utilisateur avec une regex.
				
				if($sqluserd['user_principal'] !== $_SESSION['pseudo']){ // ici, on empêche un bug qu'il y ait plusieurs message.
					
					echo '<div class="flexaffconv">';
					
					if($_SESSION['pseudo'] == $sqluserd['user_principal'] || $_SESSION['level'] == 0){	// On affiche le droit administrateurs de sélectionner les topics pour les supprimer ou les bouger.
						echo '<INPUT type="checkbox" name="del[]" value="'.$sqluserd['id'].'">';
					}
					
					echo '
					<div id="flex1" class="auteurmsg">'.$sqluserd['user_principal'].'</div>
					<div id="flex3" class="titremsg"><a href="messagerie.php?id='.$sqluserd['id'].'">'.$sqluserd['objet'].'</a></div>
					<div id="flex2" class="userinmsg">'.$sqluserd['user_distant'].'</div>
					<div class="nbcom">1</div>
					
					</div><hr width=\"100%\" size=\"1\" />'; // <div class="flexaffconv">';
				}
			}
		}
		$requserd->closeCursor();

		if($nbcom == 0) {
			echo '<div id="annonce">Aucun message présent dans la boite de réception, vous pouvez en envoyer un <a href="messagerie.php?new=1">ici</a></div>';
		}

		echo '<div id="buttondelmsg"><INPUT type="submit" name="delete" value="Supprimer" /></div>';
		echo '</form>';
		echo '</div>'; // <div id="menumarg">';
		exit;
	}
	
	public function newMsg(){
		$bdd = connectSql();
		
		echo '<div id="menumarg">';
		// On fait un bouton Retour boite de réception.
		echo '<div class="buttonRetourMsgri"><a href="messagerie.php"><- Retour</a></div>';
		echo '<h2>Nouveau message<hr width=\"100%\" size=\"1\" /></h2>'; // On affiche le titre
		
		echo '<div id="flexmsgcontact">';
		echo '<div class="order1">';
		echo '<div id="borderbg">';

		echo '<form id="formsubmit" action="messagerie.php?sendMSG=1" method="post">';
		
		echo 'Pseudonyme du Contact : <textarea name="post_user_distant" class="low"></textarea><br/>';
		
		echo 'Objet/Titre : <textarea name="post_objet" class="low"></textarea><br/><br/>';
		
		echo '<textarea name="post_message" id="editor">
		'.$_SESSION['contenumsg_retour'].'
		</textarea>';

		echo '<br/><button id="savebutton">Enregistrer</button>';

		// on appel ckeditor4 pour changer l'éditeur de texte "<textarea>"
		echo '<script src="inc/create_ckeditor.js"></script>';
		
		echo '</form>';
		
		echo '</div>'; // <div id="borderbg">';
		echo '</div>'; // <div id="order1">';
		
		echo '<div class="order2">';
		echo '<div id="borderbg">';
		
		echo '<h2>Contacts<hr width=\"100%\" size=\"1\" /></h2>';
		
		$reqmsgricontact = $bdd->query('SELECT id,username,level FROM user ORDER BY username ASC');
		
		while($sqlmsgrizicontact = $reqmsgricontact->fetch()){
			if($sqlmsgrizicontact['level'] == 0){
				echo '<span style="color: red">'.$sqlmsgrizicontact['username'].'</span>';
			}else{
				echo $sqlmsgrizicontact['username'];
			}
			
			echo ' - ';
		}
		$reqmsgricontact->closeCursor();
		
		echo '</div>'; // <div id="borderbg">';
		echo '</div>'; // <div id="order2">';
		
		echo '</div>'; // <div id="flexmsgcontact">';
		
		echo '</div>'; // <div id="menumarg">';
		
		exit;
	}
	
	public function sendMsg(){
		$bdd = connectSql();
		
		// On envoit les post du message sur la bases de donnée avec un antispam ...
		// Je vérifie que le dernier message poster par l'utilisateur dépasse pas 2 minutes
			$timeneeded = $_SESSION['lastmsg']+2;
			if($timeneeded <= date('ymdhi') || $_SESSION['level'] == '0'){
				
				//  on enregistre l'article sur la bases de données
				$reqmsgbdd = $bdd->prepare('INSERT INTO messagerie (user_principal, user_distant, objet, message, date, is_new) VALUES(:authorpsd, :pseudoscontact, :objet, :message, NOW(), 1)');

				$reqmsgbdd->execute(array(
					'authorpsd' => $_SESSION['pseudo'],
					'pseudoscontact' => $_POST['post_user_distant'],
					'objet' => $_POST['post_objet'],
					'message' => $_POST['post_message']
				));
				$reqmsgbdd->closeCursor();
				// Je déclare l'heure actuel dans la variable session "lastmsg" comme étant l'heure du dernier message poster
				$_SESSION['lastmsg'] = date('ymdhi');
				
				echo '<div id="borderbgnomarg"><div id="bordercenter">';
				echo '<div id="center"><p>Message envoyer avec succés.<br/>';
				echo '</div></div></div>';
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("messagerie.php")}, 2900);</script>';
				exit;
			}else{
				echo '<div id="annonce">Anti-spam : Vous avez poster un commentaire ou un sujet il y à moins de 2 minutes.<br/>Veuillez patienter ...<br/>';
				$_SESSION['contenumsg_retour'] = $_POST['post_message'];
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("'.$_SERVER["HTTP_REFERER"].'")}, 3800);</script>';
				exit;
			}
		
		
		
	}
	
	public function modMSGBR($msgid){
		$bdd = connectSql();
		
		// On vérifie d'abord que l'utilisateur est bien l'auteur du sujet ou un admin
		// Pour ce faire on récupere des données sur phpmyadmin
		$reqmodmsg = $bdd->prepare('SELECT id, user_principal, user_distant, objet, message FROM messagerie WHERE id=:idmsg');

		$sqlmodmsg = $reqmodmsg->execute(array('idmsg' => $msgid));
		
		while($sqlmodmsg = $reqmodmsg->fetch()){
			$id = $sqlmodmsg['id'];
			$auteur = $sqlmodmsg['user_principal'];
			$titre = $sqlmodmsg['objet'];
			$contacts = $sqlmodmsg['user_distant'];
			$message = $sqlmodmsg['message'];
		}
		$reqmodmsg->closeCursor();
		
		// Puis on lance la condition
		if($auteur == $_SESSION['pseudo'] || $_SESSION['level'] == "0"){
			// On fait un bouton Retour boite de réception.
			echo '<div class="buttonRetourMsgri"><a href="messagerie.php">Retour</a></div>';
			
			echo '<div id="menumarg">';
			echo '<h2>Nouveau message<hr width=\"100%\" size=\"1\" /></h2>'; // On affiche le titre
			
			echo '<div id="flexmsgcontact">';
			
			echo '<div class="order1">';
			echo '<div id="borderbg">';
			
			echo '<form id="formsubmit" action="messagerie.php?mod='.$id.'&sendMOD=1" method="post">';
			
			echo 'Pseudonyme du Contact : <textarea name="post_user_distant" class="low">'.$contacts.'</textarea><br/>';
			
			echo 'Objet/Titre : <textarea name="post_objet" class="low">'.$titre.'</textarea><br/><br/>';
			
			echo '<textarea name="post_message" id="sceditor">
			'.$message.'
			</textarea>';
			echo '<element onclick="changedBBcode()" id="savebutton">Modifier</element></div>';
				
			// On ajoute le script qui met la barre BBcode SCeditor sur le textarea. 
			echo "<script src='inc/sceditor_NORMALscript.js'>";
			echo "</script>";
			
			echo '</form>';
			
			echo '</div>'; // <div id="borderbg">';
			echo '</div>'; // <div id="order1">';
			
			echo '<div class="order2">';
			echo '<div id="borderbg">';
			
			echo '<h2>Contacts<hr width=\"100%\" size=\"1\" /></h2>';
			
			$reqmsgricontact = $bdd->query('SELECT id,username,level FROM user ORDER BY username ASC');
			
			while($sqlmsgrizicontact = $reqmsgricontact->fetch()){
				if($sqlmsgrizicontact['level'] == 0){
					echo '<span style="color: red">'.$sqlmsgrizicontact['username'].'</span>';
				}else{
					echo $sqlmsgrizicontact['username'];
				}
				
				echo ' - ';
			}
			$reqmsgricontact->closeCursor();
			
			echo '</div>'; // <div id="borderbg">';
			echo '</div>'; // <div id="order2">';
			
			echo '</div>'; // <div id="flexmsgcontact">';
			
			echo '</div>'; // <div id="menumarg">';
			
			exit;
		}else{// L'utilisateur ici n'est pas autoriser à supprimer ce message.
			
			echo '<div id="annonce">Erreur : Vous n\'êtes pas autoriser à modifier ce message.</div>';
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("'.$_SERVER["HTTP_REFERER"].'")}, 3800);</script>';
			exit;
			
		}
	}
	
	public function deleteMSGBR($msgid){
		
		$bdd = connectSql();
		
		// On vérifie d'abord que l'utilisateur est bien l'auteur du sujet ou un admin
		// Pour ce faire on récupere l'auteur de l'id du message voulant être supprimer
		$reqautmsg = $bdd->prepare('SELECT user_principal FROM messagerie WHERE id=:idmsg');

		$sqlautmsg = $reqautmsg->execute(array('idmsg' => $msgid));
		
		while($sqlautmsg = $reqautmsg->fetch()){
			$auteur = $sqlautmsg['user_principal'];
		}
		$reqautmsg->closeCursor();
		
		// Puis on lance la condition
		if($auteur == $_SESSION['pseudo'] || $_SESSION['level'] == "0"){
			// je fais l'opération de supression
			try{
				// set the PDO error mode to exception
				$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				// sql to delete a record
				$reqdelmsg = $bdd->prepare("DELETE FROM messagerie WHERE id = ?");
				$reqdelmsg->execute(array($msgid));
				$reqdelmsg->closeCursor();
				
				echo "<br/><p id='information'>La conversation à bien était supprimer ainsi que tous les commentaires présent.";
			}
			catch(PDOException $e){
				echo $reqdelmsg . "<br />" . $e->getMessage();
			}
			echo "<br /> <a href='messagerie.php'>Rafraichir la page</a></p>";
			$reqdelmsg->closeCursor();
		}else{// L'utilisateur ici n'est pas autoriser à supprimer ce message.
			
			echo '<div id="annonce">Erreur : Vous n\'êtes pas autoriser à supprimer ce message.</div>';
			echo "<br /> <a href='messagerie.php'>Rafraichir la page</a></p>";
			
		}
	}
	
	public function sendMOD($varid){
		$bdd = connectSql();
		
		// On envoit les post du message sur la bases de donnée avec un antispam ...
		// Je vérifie que le dernier message poster par l'utilisateur dépasse pas 2 minutes
			$timeneeded = $_SESSION['lastmsg']+2;
			if($timeneeded <= date('ymdhi') || $_SESSION['level'] == '0'){
				
				//  on enregistre l'article sur la bases de données
				$reqmsgbdd = $bdd->prepare('UPDATE messagerie SET user_principal=:authorpsd, user_distant=:pseudoscontact, objet=:objet, message=:message, date=NOW() WHERE id = :varid');

				$reqmsgbdd->execute(array(
					'authorpsd' => $_SESSION['pseudo'],
					'pseudoscontact' => $_POST['post_user_distant'],
					'objet' => $_POST['post_objet'],
					'message' => $_POST['post_message'],
					'varid' => $varid
				));
				$reqmsgbdd->closeCursor();
				// Je déclare l'heure actuel dans la variable session "lastmsg" comme étant l'heure du dernier message poster
				$_SESSION['lastmsg'] = date('ymdhi');
				
				echo '<div id="borderbgnomarg"><div id="bordercenter">';
				echo '<div id="center"><p>Message modifier avec succés.<br/>';
				echo '</div></div></div>';
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("messagerie.php")}, 2900);</script>';
				exit;
			}else{
				echo '<div id="annonce">Anti-spam : Vous avez poster un commentaire ou un sujet il y à moins de 2 minutes.<br/>Veuillez patienter ...<br/>';
				$_SESSION['contenumsg_retour'] = $_POST['post_message'];
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("'.$_SERVER["HTTP_REFERER"].'")}, 3800);</script>';
				exit;
			}
		
		
		
	}
	
	public function error_session(){
		echo '<div id="annonce">Erreur : Vous devez être connecter pour afficher cette page.<br/><br/> Veuillez patienter, vous allez être redirigez dans quelques secondes..</div>';
		echo '<script language="Javascript">setTimeout(function(){document.location.replace("connexion.php")}, 3800);</script>';
		exit;
		
	}
	
	public function errornewMSG($var1){
		echo '<div id="annonce">'.$var1.'</div>';
		$_SESSION['contenumsg_retour'] = $_POST['post_message'];
		echo '<script language="Javascript">setTimeout(function(){document.location.replace("messagerie.php?new=1")}, 3800);</script>';
		exit;
		
	}
	
	public function error($var1){
		echo '<div id="annonce">'.$var1.'</div>';
		echo '<script language="Javascript">setTimeout(function(){document.location.replace("messagerie.php")}, 3800);</script>';
		exit;
		
	}
}

?>