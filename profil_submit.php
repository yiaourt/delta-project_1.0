<?php session_start();
	
	require('inc/head_head.php'); ?>

	<!-- tête du site -->
	<header>
	
	<?php // On ajoute le titre + connexion de l'utilisateur
	require("inc/header_menu.php"); ?>
	</header>
	<!-- tête du site -->
	
	<!-- corps du site -->
    <body>
	
	<?php
	// Donc, si la variable session pseudo est vide alors on demande à l'utilisateur d'être connecter.
	if(empty($_SESSION['pseudo'])){
		echo '<div id="borderbgnomarg"><div id="bordercenter">';
		echo '<div id="center"><p>Vous devez être connecter pour afficher cette page.</p>';
		echo '<a href="connexion.php">Connectez-vous ...</a>';
		echo '</div></div></div>';
		exit;
	}else{
		// Ici on vérifie que le mot de passe de l'utilisateur correspond
		//  Récupération de l'utilisateur et de son pass hashé

		$requsr = $bdd->prepare('SELECT id, pass, username, level, img_profil FROM user WHERE username = :sessionpseudo');

		$requsr->execute(array(
		'sessionpseudo' => $_SESSION['pseudo']));

		$sqluser = $requsr->fetch();
		$requsr->closeCursor();
		//on récupère le mdp de vérification et on le vérifie
		$passverif = $_POST['mdpp'];
		$isPasswordCorrect = password_verify($passverif, $sqluser['pass']);
		if (!$isPasswordCorrect) { // Si le mot de passe n'est pas correct...
			echo '<div id="borderbgnomarg"><div id="annonce">';
			echo '<div id="center"><p>Mauvais mot de passe, vous allez être rediriger...</p>';
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("profil.php?error=1")}, 3900);</script>';
			exit;
		}
		// On récupere la classe profil_update dans profil/class/profil_update.php
		require('profil/class/profil_update.php');
		$profil_update = new profil_update;
		// On regarde ce que l'utilisateur veut modifier sur son profil et on modifie sur la base de donnees
		
		/************************************************************
		* son nom d'utilisateur
		*************************************************************/
		if(!empty($_POST['usr'])){
			$profil_update->usrUpdate();
		}
		
		/************************************************************
		* son image de profil
		*************************************************************/
		if(!empty($_FILES['imgp'])){
			$profil_update->imgUpdate();
		}
			
		/************************************************************
		* son mot de passe
		*************************************************************/
		if(!empty($_POST['newpass1']) && !empty($_POST['newpass2'])){ 
			$profil_update->mdpUpdate();
		}
		/************************************************************
		* son aresse e-mail
		*************************************************************/
		if(!empty($_POST['newmail'])){ 
			
			$newmail = $_POST['newmail'];
			$profil_update->mailUpdate($newmail);
			
		}
		
	}
		?>
    </body>
	<!-- (fin) corps du site -->
</html>