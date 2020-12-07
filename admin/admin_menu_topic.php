<?php	

	// // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 
	// Des fonctions, configuration d'accueil :
	require('admin/class/admin_home_gestion.php'); 
	// Configuration du forum.
	require('admin/class/admin_forum_gestion.php');
	
	
	$home_gestion = new home_gestion;
	$forum_gestion = new forum_gestion;
	
	// Je met un titre
	echo '<div id="menumarg">';
	echo '<div id="bordernomarg"></div>';
	echo '<div class="titletext">Gestion Forum/Accueil</div>';
	echo '<div id="border"></div>';
	
	echo '<div id="borderfloat">';
		echo "<li><a href='panel_admin.php?menu=topic&mod=home'>Accueil</a> | ";	// 'home'
		echo "<a href='panel_admin.php?menu=topic&mod=forum'>Forum</a></li>";	// 'forum'
	echo '</div>';
	
	// Et on conditionne le tout.
	if(!empty($_GET['mod']) && $_GET['mod'] == 'home'){ 
		$home_gestion->afficheHome(); // Page qui affiche les articles 'home'
	}
	if(!empty($_GET['mod']) && $_GET['mod'] == 'home_preview'){ 
		$home_gestion->afficheHomebrouillons(); // Page qui affiche les brouillons 'home_preview'
	} 
	if(!empty($_GET['mod']) && $_GET['mod'] == 'home_new'){
		$home_gestion->newHome(); // Page qui affiche la créations d'un nouvel articles 'home' ou 'home_preview'
	}
	if(!empty($_GET['mod']) && $_GET['mod'] == 'mod'){
		$home_gestion->modHome(); // modifie un article 'home' ou 'home_preview'
	}
	if(!empty($_GET['mod']) && $_GET['mod'] == 'delete'){
		$home_gestion->deleteHome(); // supprimme un article 'home' ou 'home_preview'
	}
	
	// Ci-dessous les pages qui submit ...
	if(!empty($_GET['submit']) && $_GET['submit'] == '1' && !empty($_POST)){
		$home_gestion->submitHome(); // On envois le nouvelle articles ou le nouveau brouillon...
	}elseif(!empty($_GET['mod']) && $_GET['mod'] == 1){
		$home_gestion->updateHome(); // On envoi la modification d'un article ou d'un brouillon...
		
	}elseif(empty($_POST) && !empty($_GET['submit'])){
		echo '<br/><div id="annonce">Erreur Impossible!</div>';
	}
	
	
	if(!empty($_GET['mod']) && $_GET['mod'] == 'forum_new'){
		$forum_gestion->newForum(); // Créer une nouvelle catégorie ou sous-catégorie dans le forum.
	}
	
	if(!empty($_GET['mod']) && $_GET['mod'] == 'delete_forum'){
		if(!empty($_GET['id'])){
			$forum_gestion->deleteForum(); // supprime une catégorie ainsi que toutes ces sous-catéogries, sujets et commentaires ...
		}else{
			echo '<div id="annonce">Erreur : la catégorie id = '.$_GET['id'].' n\'existe pas.</div>';
			echo '<a href="panel_admin.php?menu=topic&mod=home">Retour ...</a>';
		}
	}
	
	if(!empty($_GET['mod']) && $_GET['mod'] == 'delete_sous_forum'){
		if(!empty($_GET['id'])){
			$forum_gestion->deletesousForum(); // Supprime une sous-catégorie ainsi que ces sujets et commentaires ...
		}else{
			echo '<div id="annonce">Erreur : la sous-catégorie n\'existe pas.</div>';
			echo '<a href="panel_admin.php?menu=topic&mod=home">Retour ...</a>';
		} 
	}
	
	if(!empty($_GET['mod']) && $_GET['mod'] == 'mod_forum'){
		$forum_gestion->modForum(); // Modifie une catégorie.
	}elseif(!empty($_GET['mod']) && $_GET['mod'] == 'mod_forum_order'){
		$forum_gestion->modorderForum(); // Modifie l'ordre des catégories.
	}elseif(!empty($_GET['mod']) && $_GET['mod'] == 'mod_sous_forum'){
		$forum_gestion->modsousForum(); // Modifie une sous-catégorie.
	}elseif(!empty($_GET['mod']) && $_GET['mod'] == 'mod_description'){
		$forum_gestion->modDescription(); // Modifie la description d'une sous-catégorie.
	}elseif(!empty($_GET['mod']) && $_GET['mod'] == 'mod_order_sf'){
		$forum_gestion->modordersf(); // Modifie l'ordre d'une sous-catégorie.
	}
	
	if(!empty($_GET['mod']) && $_GET['mod'] == 'forum'){
		
		echo "<div class='titleblue'>Gestion du forum</div><div id='border2'></div><br/>";
		
		if(empty($_GET['param'])){ // Si on est pas, dans les paramètres du forum
			echo '<div class="topiccontent">';
			echo '	<form action="panel_admin.php?menu=topic&mod=forum_new" method="post">';
			echo '		<div>Ajouter une catégorie : <input type="text" name="new_forum" />';
			echo '		<input type="submit" name="add_new_forum" value="Ajouter"/></form></div>';
			echo '| <a href="panel_admin.php?menu=topic&mod=forum&param=mod">Paramètres Forum</a>';
			echo '</div>';
			$forum_gestion->afficheForum(); // Affiche les catégories et sous-catégories du forum_gestion
			
		}else{ // Si on y est...
			
			echo '<a href="panel_admin.php?menu=topic&mod=forum"><- Retour</a>';
			
			if(!empty($_GET['param']) && $_GET['param'] == 'mod'){
				$forum_gestion->afficheParamforum();
			}
			
			if(!empty($_GET['param']) && $_GET['param'] == 'submit_oss'){
				
				if(empty($_POST['name'])){
					$forum_gestion->error('Erreur : Vous devez choisir un nom(court) valide.');
				}elseif(empty($_POST['couleur'])){
					$forum_gestion->error('Erreur : Vous devez choisir une couleur valide.');
				}else{
					$forum_gestion->submitOSS($_POST['name'], $_POST['couleur']);
				}
			}
			
			if(!empty($_GET['param']) && $_GET['param'] == 'submit_modoss'){
				if(empty($_POST['name'])){
					$forum_gestion->error('Erreur : Vous devez choisir un nom(court) valide.');
				}elseif(empty($_POST['couleur'])){
					$forum_gestion->error('Erreur : Vous devez choisir une couleur valide.');
				}else{
					$forum_gestion->submitmodOSS($_POST['name'], $_POST['couleur']);
				}
			}
			
			if(!empty($_GET['param']) && $_GET['param'] == 'delete_oss'){
				$forum_gestion->deleteOSS();
			}
		}
		echo '</div>'; // <div id="menumarg">
	}
	
	if(empty($_GET['mod'])){
		echo '<br/><br/>'; // Simple ligne pour le float qui bug si l'utilisateur est nulle-part que sur la page panel_admin.php?menu=topic
		echo '</div>'; // <div id="menumarg">
	}
?>