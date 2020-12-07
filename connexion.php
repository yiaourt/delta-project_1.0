<?php session_start();
require('inc/head_head.php'); ?>

	<!-- tête du site -->
	<?php 
		// On déclare ou est l'utilisateur sur le site
		$_SESSION['WIU'] = 'connexion';

		require("inc/header_menu.php"); ?>
		
	<!-- tête du site -->
	
	<!-- corps du site -->
    <body>

    	<?php // On appelle la barre de menu au top qui s'anime avec scrollMagic. 
		$nav->nav_barre_top(); //(fonction qui fait parti de la classe "menu_navigation.php")
	

		// Si on reviens de l'inscription.
		if(!empty($_GET['return'])){

			if($_GET['return'] == 1){
				echo '<br/><br/><p id="information">Votre inscription à bien était enregistrer, veuillez patienter quelques secondes,<br/> vous allez être rediriger sur la page d\'accueil...<br/><br/><progress id="prog" max=100></p>';
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("index.php")}, 4166);</script>';
				exit;
			}elseif($_GET['return'] == 2){
				echo '<br/><br/><p id="information">Connexion réussie,<br/> vous allez être rediriger sur la page d\'accueil...<br/><br/><progress id="prog" max=100></p>';
				echo '<script language="Javascript">setTimeout(function(){document.location.replace("index.php")}, 4166);</script>';
				exit;
			}

		}

		if(!empty($_SESSION['pseudo'])){

			echo "<p id='annonce'>Vous êtes déjà connecter, vous allez être rediriger vers la page d'accueil...<br/><br/><progress id='prog' max=100></p>";

			echo '<script language="Javascript">setTimeout(function(){document.location.replace("index.php")}, 4200);</script>';
			exit;
		}
		
		if(!empty($_GET['error'])){

			echo '<p id="annonce">Erreur, veuillez réessayer.</p>';
			$_SESSION['number'] = 0;

		}?>

		<div id="borderbg">
			<form action="submit/connexion_submit.php" method="post">
				<p id="center">Pseudonyme :
					<input type="text" name="user" /></p>
				<p id="center">Mot de passe :
					<input type="password" name="passpass" /></p>
				<p id="center"><input type="submit" value="Valider" /></p>
			</form>
			<p id="center"><a href="inscription.php">Vous n'êtes pas encore inscrit ?</a></p>
		</div>
    </body>
	<!-- (fin) corps du site -->
</html>