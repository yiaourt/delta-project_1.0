
<?php session_start();
	// cerveau du site
	require('inc/head_head.php');
	
	// On déclare ou est l'utilisateur sur le site
	$_SESSION['WIU'] = 'inscription';
	
	// tête du site
	require("inc/header_menu.php");

	echo '<body>';

	// On appelle la barre de menu au top qui s'anime avec scrollMagic.
	$nav->nav_barre_top(); //(fonction qui fait parti de la classe "menu_navigation.php")

	echo '<article>';
	// On vérifie que l'utilisateur n'est pas déjà connecté
	if(!empty($_SESSION['pseudo'])){ 

		echo "<p id='annonce'>Vous êtes déjà connecter et inscrit, vous allez être rediriger vers la page d'accueil...<br/><br/><progress id='prog' max=100></p>";

		echo '<script language="Javascript">setTimeout(function(){document.location.replace("index.php")}, 4200);</script>';
		exit;
	}
	
	echo '<div id="center"><h1><u>Inscription :</u></h1><span id="information">L\'inscription est gratuite et vous permettra d\'accéder à tous les services de "Delta-Project" (Forum, Boutique en ligne, Tchat, etc...)</span></div><hr/>';

	// On vérifie qu'il ni à pas d'érreurs aprés envois du formulaire.
	if(!empty($_GET['error'])){
		if($_GET['error'] == 1){
			echo "<p id='annonce'>Vous devais remplir tous les champs.</p>";
		}
		elseif($_GET['error'] == 2){
			echo "<p id='annonce'>Votre mot de passe n'est pas le même.</p>";
		}
		elseif($_GET['error'] == 3){
			echo "<p id='annonce'>Le pseudonyme doit contenir des lettres ou des chiffres<br/>Les caractères spéciaux sont interdit.</p>";
		}
		elseif($_GET['error'] == 4){
			echo "<p id='annonce'>Le pseudonyme existe déjà.</p>";
		}
		elseif($_GET['error'] == 5){
			echo "<p id='annonce'>L'adresse e-mail rentrer doit être correcte. (mail@mail.mail)</p>";
		}
	}

	echo '<div id="inscription">
	<form action="submit/inscription_submit.php" method="post">
		
		<label class="morepx" for="username">Nom d\'utilisateur : </label><input type="text" name="username" id="username"/>
		<br/><br/>

		<label class="morepx" for="mail">Adresse e-mail : </label><input type="text" name="mail" id="mail" />
		<br/><br/>
		
		<label class="morepx" for="pass">Mot de passe : </label><input type="password" name="mdp1" id="mdp" />
		<br/><br/>
		
		<label class="morepx" for="pass2">Répétez le mot de passe : </label><input type="password" name="mdp2" id="mdp"/>
		<br/><br/>';

		echo '</div>'; // id="center"

	echo '<div id="menumarg"><div id="center"><u>Informations personnel de livraisons :</u></div>';

	// On vérifie qu'il ni à pas d'érreurs aprés envois du formulaire.
	if(!empty($_GET['error'])){
		if($_GET['error'] == 6){ 
			echo "<p id='annonce'>Erreur : Vous devez remplir tous les champs, ceci constitue vos informations personnel de livraison de commandes, nous ne serons pas tenu pour responsable si un colis ne parvient pas jusqu'à votre adresse.</p>";
		}
	}

	echo '<div id="information">';

	echo 'Sexe (*) : 

			<select name="sexe">
			  <option value=""></option>
			  <option value="homme">Homme</option>
			  <option value="femme">Femme</option>
			</select>';

	echo '<br/><br/>Prénom (*) : <input type="text" name="prenom" id="prenom"/> Nom (*) : <input type="text" name="nom" id="nom"/>';

	echo '<br/><br/>Adresse (*) : <textarea name="adresse" id="adresse"></textarea>';

	echo '<br/><br/>Ville (*) : <input type="text" name="ville" id="ville"/> Code postal (*) : <input type="text" name="postal" id="postal"/>';

	echo '</div></div>'; // id="menumarg"
	echo '<div id="center">(* = Vous n\'êtes pas obliger de remplir vos informations personnel, sauf si vous utilisez la boutique.)</div>';

	echo '<br/><br/>';

	if(!empty($_GET['error'])){
		if($_GET['error'] == 7){ 
			echo "<p id='annonce'>Erreur : Vous devez accepter la politique de confidentialité</p>";
		}
	}

	echo '<div id="information"><INPUT type="checkbox" name="CGU" /> J\'accepte la politique de confidentialité (<a href="politique de confidentialité.pdf">CGU</a>)</div>';
 
	echo '<p id="center"><input type="submit" value="Valider" /></p>';
	
	echo '</form>';

	echo '<script src="submit/js/inscription.js"></script>';


	echo '</article>';
	echo '</body>';
			?>
	
	<!-- bas du site. -->
	<footer>
		<br/><br/>© 2020 Delta Project
	</footer>
</html>