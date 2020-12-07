<?php session_start(); ?>
<?php // cerveau du site

require('inc/head_head.php'); ?>

	<!-- tête du site -->
	<?php // On déclare ou est l'utilisateur sur le site
		$_SESSION['WIU'] = 'accueil';

		require("inc/header_menu.php"); ?>
	
	<!-- corps du site -->
    <body>
	<?php 
		// On appelle la barre de menu au top qui s'anime avec scrollMagic. 
		$nav->nav_barre_top(); //(fonction qui fait parti de la classe "menu_navigation.php")

		$reqohm = $bdd->query('SELECT * FROM home');
		
		while($sqlohm = $reqohm->fetch()){
			echo '<article>';
			echo '<hr width=\"100%\" size=\"1\" />';
			echo $sqlohm['contenu'];
			if(!empty($_SESSION['pseudo']) && $_SESSION['level'] == 0){
				echo '<hr width=\"100%\" size=\"1\" /><a href=panel_admin.php?menu=topic&mod=mod&id='.$sqlohm['id'].'>Modifier</a> | <a href=panel_admin.php?menu=topic&mod=delete&id='.$sqlohm['id'].'>Supprimer</a>';
			}
			echo '</article>';
		}
		$reqohm->closeCursor();
	?>
    </body>
	
	<!-- bas du site. -->
	<footer>
		<br/><br/>© 2020 Delta Project
	</footer>
</html>
