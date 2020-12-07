<?php session_start(); ?>
<?php // cerveau du site
		require('inc/head_head.php'); ?>

	<!-- tête du site -->
	<header>
	
	<?php 
	// On déclare ou est l'utilisateur sur le site
		$_SESSION['WIU'] = 'profil';

		require("inc/header_menu.php"); ?>
	
	</header>
	<!-- tête du site -->
	
	<!-- corps du site -->
    <body>
	<?php 
		// On appelle la barre de menu au top qui s'anime avec scrollMagic. 
		$nav->nav_barre_top(); //(fonction qui fait parti de la classe "menu_navigation.php")

		// On inclus ici le système de profil pour que l'utilisateur modifie son profil.
		require("profil/body_profil.php");
	?>
    </body>
	<!-- (fin) corps du site -->
</html>