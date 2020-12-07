<?php

class nav {
		
	public function addMenu(){
		$bdd = connectSql();
		
		echo '<nav>';
		
		// ci dessous,
		// le menu de navigation dans : ('menu_navigation/menu_navigation.php')
		require('inc/menu_navigation/menu_navigation.php');

		echo '</nav>';
	}
	
	public function connexionMenu(){ // Ci dessous, le menu de connexion
		
		// On note la couleur du pseudo en fonction du level de l'utilisateur
		if(!empty($_SESSION['pseudo'])){
			
			if($_SESSION['level'] == 1){
				$varcolorlvl = '1';
			}else{
				$varcolorlvl = '0';
			}
		}
		
		if(!empty($_SESSION['pseudo'])){ // Si l'utilisateur est connecter.
			
			echo '<fieldset id="connected"> <legend>Utilisateur :</legend>';

			echo '<span id="img_user_menu">'.$_SESSION['img_profil'].'</span>'; // l'image de profil 

			echo '<div id="column_menu">';

			echo '<a id="nom_utilisateur" href="profil.php" class="colorlvl'. $varcolorlvl .'">'.$_SESSION["pseudo"]. '</a>';

			echo '<a id="button_messagerie" href="messagerie.php">Messagerie ()</a>';
			echo '<a id="button_user_panier" href="panier.php">Panier (<span id="total_panier"></span>)</a>';

			echo '<a id="button_deconnexion" href="deconnexion.php">Déconnexion</a>';

			echo '</div>';

			echo '</fieldset>';
		
		}else{
			echo '<fieldset id="noconnect"> <legend>Vous n\'êtes pas connecté(e) : </legend>';
			echo '<div class="connexioninscription">';
			echo '<a id="buttonconnect" href="connexion.php">Connexion</a>';
			echo ' - ';
			echo '<a id="buttonconnect" href="inscription.php">inscription</a>';
			echo '</fieldset>';
		}
	}

	public function nav_barre_top() {
		// Une barre de navigation sticky avec jquery
		

		// -- Version Mobile -- //
		echo '<div id="nav_barre_top_mobile">';

		// On fait logo pour le site.
		if(empty($_SESSION['style'])){ // Par défaut
			echo '<a href="index.php"><span id="left_logo"><img id="logo_mobile" src="img/logo_deltaproject_carbon.ico" alt="logo" align="middle"/></span></a>';

		}elseif(!empty($_SESSION['style']) && $_SESSION['style'] == 'carbon'){
			echo '<a href="index.php"><span id="left_logo"><img id="logo_mobile" src="img/logo_deltaproject_carbon.ico" alt="logo" align="middle"/></span></a>';

		}elseif(!empty($_SESSION['style']) && $_SESSION['style'] == 'light'){
			echo '<a href="index.php"><span id="left_logo"><img id="logo_mobile" src="img/logo_deltaproject_light.ico" alt="logo" align="middle"/></span></a>';

		}

		//On fait un titre
		echo '<span id="titre_mobile">Delta Project</span>';

		// On fait un bouton que l'on cache sur pc et que l'on affiche sur téléphone.
		echo '<a id="menu_mobile" href="javascript:void(0)"><img src="img/menu_icone.png" alt="menu_icone"/></a>';

		echo '</div>';

		// Et on fait une liste de menu qu'on affiche avec jquery
		echo '<div id="liste_mobile">';

		if(empty($_SESSION['pseudo'])){ // Si l'utilisateur n'est pas connecter
			echo '<span><a href="connexion.php">Connexion</a> - <a href="inscription.php">Inscription</a></span>';
		
		}else{ // Si l'utilisateur est connecter ...
			echo '<a href="profil.php">'.$_SESSION['pseudo'].' '.$_SESSION['icone_img_profil'].'</a>';
		}

		echo '<a id="link_liste_mobile" href="forum.php">Forum</a>';
		echo '<a id="link_liste_mobile" href="shop.php">Shop 3D</a>';
		echo '<a id="link_liste_mobile" href="irc.php">IRC</a>';

		echo '</div>';



		// -- Version Pc -- //

		echo '<div id="nav_barre_top">';

		// On fait un bloc du logo qu'on cache avec le css et qu'on affiche avec ScrollMagic
		if(empty($_SESSION['style'])){ // Par défaut
			echo '<span id="floatleft"><img id="logodelta_theme" class="invisible" src="img/logo_deltaproject_carbon.ico" alt="logo" align="middle"/></span>';

		}elseif(!empty($_SESSION['style']) && $_SESSION['style'] == 'carbon'){
			echo '<span id="floatleft"><img id="logodelta_theme" class="invisible" src="img/logo_deltaproject_carbon.ico" alt="logo" align="middle"/></span>';

		}elseif(!empty($_SESSION['style']) && $_SESSION['style'] == 'light'){
			echo '<span id="floatleft"><img id="logodelta_theme" class="invisible" src="img/logo_deltaproject_light.ico" alt="logo" align="middle"/></span>';

		}

		echo '<span id="titre_delta_theme">Thèmes :</span>';

		echo'<span id="theme_menu">
		<FORM name="Choix">
		<SELECT name="Liste" onChange="changeTheme()">';
		
		if(empty($_SESSION['style'])){ // Par défaut
			echo '<OPTION VALUE="'.$_SERVER['PHP_SELF'].'?';if(!empty($_SERVER['QUERY_STRING'])){ echo $_SERVER['QUERY_STRING'].'&';} echo 'theme=light">light</OPTION>';
			echo '<OPTION VALUE="'.$_SERVER['PHP_SELF'].'?';if(!empty($_SERVER['QUERY_STRING'])){ echo $_SERVER['QUERY_STRING'].'&';} echo 'theme=carbon">carbon</OPTION>';
		}elseif(!empty($_SESSION['style']) && $_SESSION['style'] == 'carbon'){
			echo '<OPTION VALUE="'.$_SERVER['PHP_SELF'].'?';if(!empty($_SERVER['QUERY_STRING'])){ echo $_SERVER['QUERY_STRING'].'&';} echo 'theme=carbon">carbon</OPTION>';
			echo '<OPTION VALUE="'.$_SERVER['PHP_SELF'].'?';if(!empty($_SERVER['QUERY_STRING'])){ echo $_SERVER['QUERY_STRING'].'&';} echo 'theme=light">light</OPTION>';
		}elseif(!empty($_SESSION['style']) && $_SESSION['style'] == 'light'){
			echo '<OPTION VALUE="'.$_SERVER['PHP_SELF'].'?';if(!empty($_SERVER['QUERY_STRING'])){ echo $_SERVER['QUERY_STRING'].'&';} echo 'theme=light">light</OPTION>';
			echo '<OPTION VALUE="'.$_SERVER['PHP_SELF'].'?';if(!empty($_SERVER['QUERY_STRING'])){ echo $_SERVER['QUERY_STRING'].'&';} echo 'theme=carbon">carbon</OPTION>';
		}
			  
		echo '</SELECT>
			  </FORM>
			  </span>';

		echo '<span id="icone_sticky">';
		// On fait un lien vers la page d'accueil cacher qui s'afficheras avec scrollmagic.
		echo '<a id="home_icone" class="invisible" href="index.php" title="Accueil" >Accueil</a>';

		// On fait un lien vers le forum cacher qui s'afficheras avec scrollmagic.
		echo '<a id="forum_icone" class="invisible" href="forum.php" title="Le forum" >Forum</a>';

		// On fait un lien vers le shop cacher qui s'afficheras avec scrollmagic.
		echo '<a id="shop_icone" class="invisible" href="shop.php" title="Le shop 3D">Shop 3D</a>';

		// On fait un lien vers le irc cacher qui s'afficheras avec scrollmagic.
		echo '<a id="irc_icone" class="invisible" href="irc.php" title="Le tchat de Delta">IRC</a>';

		echo '</span>';

		// On fait aussi 2 liens de connexion inscription si l'utilisateur n'est pas connecter...
		if(empty($_SESSION['pseudo'])){
			echo '<span id="connexion_sticky" class="invisible"><a href="connexion.php">Connexion</a> - <a href="inscription.php">Inscription</a></span>';
		
		}else{ // Si l'utilisateur est connecter ...
			echo '<a id="connexion_sticky" class="invisible" href="javascript:void(0)" onclick="afficheUser()">'.$_SESSION['pseudo'].' '.$_SESSION['icone_img_profil'].'</a>';
			
			// On fait un menu cacher qui se déroule avec la fonction afficheUser()
			echo '<div id="profil_deroulant_sticky" onmouseleave="cacheUser()">';

			echo '<a href="profil.php">Mon Profil</a><br/><hr>';
			echo '<a href="profil.php">Boite de réception</a><br/><br/><hr>';

			echo '<a href="panier.php">Panier (<span id="total_nav_barre_panier"></span>)</a><br/><hr>';

			echo '<a href="deconnexion.php">Déconnexion</a>';

			echo '</div>';
		}

		echo '</div>';

		echo '<script src="inc/menu_navigation/js/menu_sticky.js"></script>';

		// On rafraichis les notifications toutes les 2 secondes.
		echo '<script src="shop/js/refresh.js"></script>';
	}
} ?>