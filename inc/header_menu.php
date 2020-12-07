<?php
	echo '<header>'; // On créer ici le header
	
	require('menu_navigation/nav.php'); // On récupere la classe qui affiche le menu navigation.
	$nav = new nav;
	
	
	echo '<div id="menunavigation">'; // le "div id" css du menu de navigation

	$nav->addmenu();
	
	echo '</div>'; // '<div id="menunavigation">';


	
	
	echo '<div id="window">'; // bloc css, pour les thèmes et le lien d'administration.

	$nav->connexionMenu(); // on affiche le menu de connexion.
	
	if(!empty($_SESSION['id']) && $_SESSION['level'] == 0){
		echo '<a href="panel_admin.php">Panneau d\'administrations</a>'; // On affiche un lien pour le panneau d'administrations
	}
	
	echo '</div>';

	echo '</header>'; // On ferme ici le header
?>