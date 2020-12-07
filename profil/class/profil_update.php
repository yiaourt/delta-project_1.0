<?php 

class profil_update{
	
	public function usrUpdate(){
		$bdd = connectSql();
		
		// On vérifie que le pseudo rentrer est conforme.
		if(preg_match("#[^a-zA-Z0-9_]#", $_POST['usr'])){
			
			echo '<div id="article"><div id="annonce">';
			echo '<div id="center"><p>Erreur: Le pseudonyme doit contenir des lêttres ou des chiffres<br/>Les caractères spéciaux sont interdit, vous allez être rediriger...</p><progress id=\'prog\' max=100>';
			
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("profil.php")}, 4300);</script>';
			echo '</div></div></div>';
			exit;
		}

		// on vérifie que son nom d'utilisateur n'existe pas déjà
		$req = $bdd->prepare("SELECT id FROM user WHERE username = :postuser");
		
		$req->execute(array(
				'postuser' => $_POST['usr']
		));
		if(!empty($resultat['id'])){
			echo '<div id="article"><div id="annonce">';
			echo '<div id="center"><p>Erreur: Le pseudonyme existe déjà, veuillez réessayer, vous allez être rediriger...</p><progress id=\'prog\' max=100>';
			
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("profil.php")}, 4100);</script>';
			echo '</div></div></div>';
			exit;
		}
		
		$resultat = $req->fetch();

		// puis, on modifie son nom d'utilisateur.
		$requpdate = $bdd->prepare('UPDATE user SET username = :usermodif WHERE username = :sessionpseudo');

		$requpdate->execute(array(
			'usermodif' => $_POST['usr'],
			'sessionpseudo' => $_SESSION['pseudo']
		));
		$requpdate->closeCursor();
	
		// puis on recréer la session
		$_SESSION['pseudo'] = $_POST['usr'];

		echo '<p id="information">Votre nom d\'utilisateur à bien était modifier.</p>';
		echo '<script language="Javascript">setTimeout(function(){document.location.replace("profil.php")}, 4200);</script>';
		exit;
	}
	
	public function imgUpdate(){
		$bdd = connectSql();
		if(!empty($_FILES['imgp'])){
		
			if($_FILES['imgp']['error'] > 0) $msg = 'Erreur lors du transfert';
			if($_FILES['imgp']['size'] > 2000000) $msg = "Erreur : Le fichier est trop volumineux";

			//1. strrchr renvoie l'extension avec le point (« . »).
			//2. substr(chaine,1) ignore le premier caractère de chaine.
			//3. strtolower met l'extension en minuscules.

			$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
			$extension_upload = strtolower(  substr(  strrchr($_FILES['imgp']['name'], '.')  ,1)  );
			
			if(in_array($extension_upload, $extensions_valides)){

				$image_sizes = getimagesize($_FILES['imgp']['tmp_name']);
				
				if ($image_sizes[0] > '250' OR $image_sizes[1] > '250'){
					$msg = "Erreur : L'image est trop grande (L: 250px H: 250px)";
				}
		
				$racine_nom = $_SERVER['DOCUMENT_ROOT'].'/img/profil/'.$_SESSION['id'].'.'.$extension_upload;
				
				$nom = $_SESSION['id'].'.'.$extension_upload;

				if (is_uploaded_file($_FILES['imgp']['tmp_name'])) {
					
					if(move_uploaded_file($_FILES['imgp']['tmp_name'], $racine_nom)){
						
						if(empty($msg)){
							
							$newimgp = "<img src='img/profil/".$nom."' alt='Image de Profil' align='middle'>";
							$newiconeimgp = "<img id='icone' src='img/profil/".$nom."' alt='Image de Profil' align='middle'>";

							$requpimg = $bdd->prepare('UPDATE user SET img_profil = :newimgp, icone_img_profil = :newiconeimgp WHERE username = :sessionpseudo');

							$requpimg->execute(array(
								'newimgp' => $newimgp,
								'newiconeimgp' => $newiconeimgp,
								'sessionpseudo' => $_SESSION['pseudo']
							));
							$requpimg->closeCursor();
							
							$_SESSION['img_profil'] = $newimgp;
							$_SESSION['icone_img_profil'] = $newiconeimgp;
							
							echo '<div id="article"><div id="information">';
							echo '<div id="center"><p>Transfert d\'image de profil réussit sur le profil, vous allez être rediriger...</p><progress id=\'prog\' max=100>';
							
							echo '<script language="Javascript">setTimeout(function(){document.location.replace("profil.php")}, 4200);</script>';
							echo '</div></div></div>';
							exit;
						}else{
							echo '<div id="article"><div id="annonce">';
							echo '<div id="center"><p>'.$msg.'</p>Vous allez être rediriger...<br/><br/>
							<progress id=\'prog\' max=100>';
							
							echo '<script language="Javascript">setTimeout(function(){document.location.replace("profil.php")}, 4200);</script>';
							echo '</div></div></div>'; 
							exit;
						}
					}else{
						echo '<div id="article"><div id="annonce">';
						echo '<div id="center"><p>Erreur : Problème de fichier, veuillez contacter un administateur</p>';

						echo '<script language="Javascript">setTimeout(function(){document.location.replace("profil.php")}, 4200);</script>';
						echo '</div></div></div>'; 
						exit;
					}
				}
			}
		}else{
			echo '<div id="borderbgnomarg"><div id="bordercenter">';
			echo '<div id="center"><p>Erreur : Aucune image sélectionner.</p>Vous allez être rediriger...<br/><br/>
							<progress id=\'prog\' max=100>';
			
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("profil.php")}, 4200);</script>';
			echo '</div></div></div>'; 
		}
	}
	
	public function mdpUpdate(){
		$bdd = connectSql();
		$newpass1 = $_POST['newpass1'];
		$newpass2 = $_POST['newpass2'];
		if($newpass1 == $newpass2){
			$newpass = password_hash($newpass1, PASSWORD_DEFAULT);
			$requpusr = $bdd->prepare('UPDATE user SET pass = :newpass WHERE username = :sessionpseudo');

			$requpusr->execute(array(
				'newpass' => $newpass,
				'sessionpseudo' => $_SESSION['pseudo']
			));
			session_destroy();
			$requpusr->closeCursor();
			echo '<div id="borderbgnomarg"><div id="bordercenter">';
			echo '<div id="center"><p>Mot de passe modifier avec succer.<br/></p>';
			echo '<a href="connexion.php">Reconnectez-vous avec le nouveau mot de passe ...</a>';
			echo '</div></div></div>';
			exit;
		}else{
				$requpusr->closeCursor();
				echo '<div id="borderbgnomarg"><div id="bordercenter">';
				echo '<div id="center"><p>Erreur : Les mots de passe ne correspondent pas.</p>';
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("profil.php")}, 4200);</script>';
				echo '</div></div></div>'; 
		}
	}
	
	public function mailUpdate($newmailvar){
		$bdd = connectSql();
		$requpmail = $bdd->prepare('UPDATE user SET mail = :newmail WHERE username = :sessionpseudo');

		$requpmail->execute(array(
			'newmail' => $newmailvar,
			'sessionpseudo' => $_SESSION['pseudo']
		));
		$requpmail->closeCursor();
		// puis on recréer la session
		$_SESSION['mail'] = $newmailvar;
		echo '<div id="borderbgnomarg"><div id="bordercenter">';
		echo '<div id="center"><p>Le mail à était modifier avec succer.<br/></p>';
		echo '<script language="Javascript">setTimeout(function(){document.location.replace("profil.php")}, 4200);</script>';
		echo '</div></div></div>';
		exit;
	}
}
?>