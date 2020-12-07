<?php
		
		// On regarde ce que l'utilisateur veut modifier sur son profil et on modifie sur la base de donnees
		
		/************************************************************
		* son nom d'utilisateur
		*************************************************************/
		if(!empty($_POST['usr'])){
			$req = $bdd->prepare('UPDATE user SET username = :usermodif WHERE id = :idpseudo');

			$req->execute(array(
				'usermodif' => $_POST['usr'],
				'idpseudo' => $_GET['id']
			));
			echo '<div id="borderbgnomarg"><div id="bordercenter">';
			echo '<div id="center"><p>Le pseudonyme à correctement était modifier.</p>';
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("panel_admin.php?menu=user&id='.$_GET['id'].'")}, 3900);</script>';
			echo '</div></div></div>'; 
			$req->closeCursor();
			exit;
		}
		
		/************************************************************
		* son image de profil
		*************************************************************/
		if(!empty($_GET['id']) && $_GET['mod'] == 'imgp'){
			
			$dir = '/img/profil/';
			
			if(!empty($_FILES['imgp'])){
				if($_FILES['imgp']['error'] > 0) $msg = 'Erreur lors du transfert';
				if($_FILES['imgp']['size'] > 25000) $msg = "Erreur : Le fichier est trop volumineux";
				$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );

				//1. strrchr renvoie l'extension avec le point (« . »).
				//2. substr(chaine,1) ignore le premier caractère de chaine.
				//3. strtolower met l'extension en minuscules.

				$extension_upload = strtolower(  substr(  strrchr($_FILES['imgp']['name'], '.')  ,1)  );
				if(in_array($extension_upload,$extensions_valides)){
					$image_sizes = getimagesize($_FILES['imgp']['tmp_name']);
					if ($image_sizes[0] > '250' OR $image_sizes[1] > '250') $msg = "Erreur : L'image est trop grande (L: 250px H: 250px)";
					//Créer un identifiant difficile à deviner
					$nom = md5(uniqid(rand(), true));
					$nom = "img/profil/{$_GET['id']}.{$extension_upload}";
					$resultat = move_uploaded_file($_FILES['imgp']['tmp_name'],$nom);
					if ($resultat){
						if(empty($msg)){
							$requsr = $bdd->prepare('UPDATE user SET img_profil = :newimgp WHERE id = :getid');

							$requsr->execute(array(
								'newimgp' => "<img src='".$nom."' alt='Image de Profil'>",
								'getid' => $_GET['id']
							));
							$requsr->closeCursor();
							echo '<div id="borderbgnomarg"><div id="bordercenter">';
							echo '<div id="center"><p>Transfert d\'image de profil réussit.</p>';
							echo '<script language="Javascript">setTimeout(function(){document.location.replace("panel_admin.php?menu=user&id='.$_GET['id'].'")}, 3900);</script>';
							echo '</div></div></div>';
							exit;
						}
					}
				}
			}else{
				echo '<div id="borderbgnomarg"><div id="bordercenter">';
				echo '<div id="center"><p>Erreur : Aucune image sélectionner.</p>';
				echo '<a href="panel_admin.php?id='.$_GET['id'].'">Retour ...</a>';
				echo '</div></div></div>';
				exit;
			}
			
			if(!empty($msg)){
				echo '<div id="borderbgnomarg"><div id="bordercenter">';
				echo '<div id="center"><p>'.$msg.'</p>';
				echo '<a href="panel_admin.php?id='.$_GET['id'].'">Retour ...</a>';
				echo '</div></div></div>'; 
				exit;
			}
			
		}
		
		if(!empty($_POST['newpass1']) && !empty($_POST['newpass2'])){ 
			if($_GET['mod'] == 'mdp'){
				$newpass1 = $_POST['newpass1'];
				$newpass2 = $_POST['newpass2'];
				if($newpass1 == $newpass2){
					$newpass = password_hash($newpass1, PASSWORD_DEFAULT);
					$requpusr = $bdd->prepare('UPDATE user SET pass = :newpass WHERE id = :getid');

					$requpusr->execute(array(
						'newpass' => $newpass,
						'getid' => $_GET['id']
					));
					$requpusr->closeCursor();
					echo '<div id="borderbgnomarg"><div id="bordercenter">';
					echo '<div id="center"><p>Mot de passe modifier avec succer.<br/></p>';
					echo '<script language="Javascript">setTimeout(function(){document.location.replace("panel_admin.php?menu=user&id='.$_GET['id'].'")}, 3900);</script>';
					echo '</div></div></div>';
					exit;
				}else{
					echo '<div id="borderbgnomarg"><div id="bordercenter">';
					echo '<div id="center"><p>Erreur : Les mots de passe ne correspondent pas.</p>';
					echo '<script language="Javascript">setTimeout(function(){document.location.replace("panel_admin.php?menu=user&id='.$_GET['id'].'")}, 3900);</script>';
					echo '</div></div></div>'; 
				}
			}
		}
		
		if(!empty($_POST['newmail'])){ 
			if($_GET['mod'] == 'mail'){
				$newmail = $_POST['newmail'];
				$requpmail = $bdd->prepare('UPDATE user SET mail = :newmail WHERE id = :getid');

				$requpmail->execute(array(
					'newmail' => $newmail,
					'getid' => $_GET['id']
				));
				$requpmail->closeCursor();
				echo '<div id="borderbgnomarg"><div id="bordercenter">';
				echo '<div id="center"><p>Le mail à était modifier avec succer.<br/></p>';
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("panel_admin.php?menu=user&id='.$_GET['id'].'")}, 3900);</script>';
				echo '</div></div></div>';
				exit;
			}
		}
	
		?>