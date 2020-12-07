<?php session_start();
	// On se connecte à sql PDO
	require('../inc/func/func_connectsql.php');
	$bdd = connectSql();

	// On récupere l'adresse ip de l'utilisateur en appelant la fonctions get_ip
	require('../inc/func/func_get_ip.php');

	// on vérifie que le formulaire n'est pas vide.
	if(empty($_POST['user'])){
		header ("Location: ../connexion.php?error=1" );
	}
	if(empty($_POST['passpass'])){
		header ("Location: ../connexion.php?error=1" );
	}
	
	$requsertobdd = $bdd->prepare('SELECT * FROM user WHERE username = :pseudo');
	$reqcbdd = $requsertobdd->execute(array(
		'pseudo' => $_POST['user']));

	while($reqcbdd = $requsertobdd->fetch()){
		if(password_verify($_POST['passpass'], $reqcbdd['pass'])) { // On vérifie que le mot de passe est correct.
			
			$_SESSION['id'] = $reqcbdd['id']; // l'identifiant utilisateur.
			$_SESSION['pseudo'] = $reqcbdd['username']; // le nom d'utilisateur.
			$_SESSION['mail'] = $reqcbdd['mail']; // l'adresse e-mail de l'utilisateur.
			$_SESSION['level'] = $reqcbdd['level']; // son niveau d'accés au site (0 = Administrateur, 1 = Utilisateur)
			$_SESSION['img_profil'] = $reqcbdd['img_profil']; // son image de profil
			$_SESSION['icone_img_profil'] = $reqcbdd['icone_img_profil'];
			$_SESSION['redo'] = '';
			$_SESSION['lastmsg'] = date('ymdhi')-03; // Variable session qui sert dans l'antispam de messages utilisateur.

			// On unserialize le tableau en bdd
			$unserialized_panier = unserialize($reqcbdd['panier']);

			// On vérifie si unserialized panier est vide.
			if(empty($unserialized_panier)){
				$unserialized_panier = array();
			}

			// On vérifie que la variable $_SESSION['total_panier'] existe
			if(empty($_SESSION['total_panier'])){

				$_SESSION['total_panier'] = 0; // Si elle n'existe pas alors on la défini à 0;
			
			}

			// On calcule ensuite le total d'articles dans le panier.
			foreach($unserialized_panier as $id){

				// On limite le nombre d'articles à 15 dans le panier
				if($_SESSION['total_panier'] < 15){
					
					// On ajoute chaques articles panier de la bdd au panier session
					$_SESSION['panier'][] = $id;

					// On incrémente le nombre d'articles.
					$_SESSION['total_panier'] = $_SESSION['total_panier'] + 1;
				}

			}
			
			// On récupere ci dessous l'ip de l'utilisateur que l'on garde dans la base de données comme étant la dernière ip de connexion
			$_SESSION['ip'] = get_ip();
			$updateip = $bdd->prepare('UPDATE user SET ip = :getip WHERE username = :pseudo');

			$updateip->execute(array(
				'getip' => get_ip(),
				'pseudo' => $_SESSION['pseudo']
			));
			$updateip->closeCursor();

			// Ci dessous, on récupere le tableau de valeurs des derniers topics et commentaires, utile notamment pour les notifications de nouveaux commentaires ou topics.
			$_SESSION['lastdateTP'] = unserialize($reqcbdd['lastdat_TP']);
			$_SESSION['lastdateCM'] = unserialize($reqcbdd['lastdat_CM']);

			// on redirige l'utilisateur avec return 2 qui contient le texte html : Connexion réussie.
			echo '<script language="Javascript">document.location.replace("../connexion.php?return=2");</script>';
			exit;
		}else{
			// on redirige l'utilisateur avec une érreur.
			echo '<script language="Javascript">document.location.replace("../connexion.php?error=1");</script>';
			exit;
		}
	}
	$requsertobdd->closeCursor();
	// on redirige l'utilisateur avec une érreur.
	echo '<script language="Javascript">document.location.replace("../connexion.php?error=1");</script>';
	exit;
  ?>
