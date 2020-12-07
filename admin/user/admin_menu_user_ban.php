<?php
		// On récupere l'ip de l'utilisateur à bannir
		
		$sqlipuser = $bdd->prepare('SELECT ip FROM user WHERE username = :getpseudo');
		$sqlipuser->execute(array(
			'getpseudo' => $_GET['ban']
		));
		$ipuser = $sqlipuser->fetch();
		$sqlipuser->closeCursor();
		
		if(empty($_GET['a'])){
			echo '<p id="annonce">Vous êtes sur le point de bannir l\'utilisateur "'.$_GET['ban'].'" veuillez donner (ou non) une raison.</p>';
			echo '<form action="panel_admin.php?ban='.$_GET['ban'].'&a=y" method="post">';
			echo '<input type="text" name="raison" />';
			echo '<input type="submit" value="Envoyer" />';
			echo '</form>';
			exit;
		}else{
			if(!empty($_POST['raison'])){
				$raison = $_POST['raison'];
			}else{
				$raison = '';
			}
			// On ajoute ensuite l'ip de l'utilisateur parmis la blacklist d'ip du forum sur la base de données.
			$req1 = $bdd->prepare('INSERT INTO blacklist (ip, Raison) VALUES(:getip, :raiso)');
			$req1->execute(array(
				'getip' => $ipuser['ip'],
				'raiso' => $raison
			));
			$req1->closeCursor();
			echo '<p id="annonce">Le bannissement à bien été effectuer, vous allez être rediriger...</p>';
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("panel_admin.php?menu=user")}, 4200);</script>';
			exit;
		}
?>