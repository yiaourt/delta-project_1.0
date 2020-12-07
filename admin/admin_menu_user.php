<?php	
		if(!empty($_GET['menu'])){
			if($_GET['menu'] == 'user'){
				// ici, je fais la gestion des utilisateurs
				
				// Je met un titre
				echo '<div id="menumarg">';
				echo '<div id="bordernomarg"></div>';
				echo '<div class="titletext">Rechercher/modifier/supprimer un utilisateur</div>';
				echo '<div id="border"></div>';
				
					// J'appelle les fonctions de la classe gestion des utilsateurs
					require('class/admin_user_gestion.php');
					$user_gestion = new user_gestion();
					
					$user_gestion->deleteUser();
					$user_gestion->searchUser();
				
					// Ensuite la liste d'utilisateur si l'admin n'est pas dans une recherche d'user..
					if(empty($_GET['search'])){
						$user_gestion->listUser();
					}
			}
		}
?>