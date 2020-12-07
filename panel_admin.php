<?php session_start();
	// cerveau du site
	require('inc/head_head.php');
 ?>

	<!-- tête du site -->
	<header>
	<?php 
	// On déclare ou est l'utilisateur sur le site
	$_SESSION['WIU'] = 'admin';

	require("inc/header_menu.php");
	
	?>
	</header>
	<!-- tête du site -->
	
	<!-- corps du site -->
    <body>
	<?php 

		// On appelle la barre de menu au top qui s'anime avec scrollMagic. 
		$nav->nav_barre_top(); //(fonction qui fait parti de la classe "menu_navigation.php")
		
			// ////////////////////////////////////////////////////////////////////////////
			// Ici se trouve l'index du panel-admin incluant plusieurs pages de menus
			// J'utiliserais les variables $_GET['menu'] pour savoir quelles fonctions afficher
			// Ou $_GET['ban'] si c'est pour bannir un utilisateur.
			// ////////////////////////////////////////////////////////////////////////////

		// On va chercher le corp du panel admin
		require('admin/admin_body.php');
			
		if(!empty($_GET['menu']) && $_GET['menu'] == 'user'){
			if(empty($_GET['id'])){
				require('admin/admin_menu_user.php'); // Le menu gestion des utilisateurs
			}else{
				if(empty($_GET['post'])){
					require('admin/user/admin_menu_user_mod.php'); // La page modification d'utilisateurs (sous menu de la page admin_menu_user.php)
				}else{
					require('admin/user/admin_menu_user_submit.php'); // La page qui enverra les données sql pour modifier un utilisateur.
				}
			}
		}
		if(!empty($_GET['ban'])){
			require('admin/user/admin_menu_user_ban.php');
		}
		
		if(!empty($_GET['menu']) && $_GET['menu'] == 'topic'){
			require('admin/admin_menu_topic.php'); // Le menu de gestion de la page d'accueil et du forum
		}

		if(!empty($_GET['menu']) && $_GET['menu'] == 'commande'){
			require('admin/admin_menu_commande.php'); // Le menu de gestion des commandes
		}
		
		if(!empty($_GET['menu']) && $_GET['menu'] == 'param'){
			require('admin/admin_menu_param.php');
		}
	?>
    </body>
	<!-- (fin) corps du site -->
</html>