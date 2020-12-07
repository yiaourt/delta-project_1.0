<?php session_start(); ?>
<?php require('inc/head_head.php'); ?>

	<!-- tête du site -->
	<?php 
	// On déclare ou est l'utilisateur sur le site
			$_SESSION['WIU'] = 'shop';

			require("inc/header_menu.php"); ?>
	
	<!-- corps du site -->
    <body>
		<?php
			// On appelle la barre de menu au top qui s'anime avec scrollMagic. 
			$nav->nav_barre_top(); //(fonction qui fait parti de la classe "menu_navigation.php")

			// On appelle donc les classes et fonctions ...
			require('shop/aff_shop.php');
			$aff_shop = new aff_shop;

			require('shop/admin_article.php');
			$admin_article = new admin_article;


			// Puis on conditionne tous les pages du magasins ...
			// ----------------------------------------------------

			if(empty($_GET['article'])){
				$aff_shop->afficheShop();
			
			}elseif($_GET['article'] == 'new'){ // Si on est dans l'ajout d'un nouvelle article.
				if(!empty($_SESSION['pseudo']) && $_SESSION['level'] == 0){ // Si l'utilisateur est bien un administrateur

					$admin_article->newArticle(); // l'utilisateur se retrouve sur la page d'un nouvelle article.
				
				}else{ // Si l'utilisateur n'est pas un administrateur on renvois une érreur
					echo '<div id="annonce"><div id="bordercenter">';
					echo "<div id='center'><p>Vous n'êtes pas autorisé à voir cette page.</p>";
					echo '<a href="shop.php">Retour</a>';
					echo '</div></div></div>';
					exit;
				}
			
			}elseif($_GET['article'] == 'new_category'){ // Si on est dans l'ajout d'un nouvelle article.
				if(!empty($_SESSION['pseudo']) && $_SESSION['level'] == 0){ // Si l'utilisateur est bien un administrateur

					$admin_article->newCategory(); // l'utilisateur se retrouve sur la page d'un nouvelle article.
				
				}else{ // Si l'utilisateur n'est pas un administrateur on renvois une érreur
					echo '<div id="annonce"><div id="bordercenter">';
					echo "<div id='center'><p>Vous n'êtes pas autorisé à voir cette page.</p>";
					echo '<a href="shop.php">Retour</a>';
					echo '</div></div></div>';
					exit;
				}
			
			}elseif($_GET['article'] == 'send_category'){ // Si on est dans l'ajout d'un nouvelle article.
				if(!empty($_SESSION['pseudo']) && $_SESSION['level'] == 0){ // Si l'utilisateur est bien un administrateur

					$admin_article->sendCategory(); // l'utilisateur se retrouve sur la page d'un nouvelle article.
				
				}else{ // Si l'utilisateur n'est pas un administrateur on renvois une érreur
					echo '<div id="annonce"><div id="bordercenter">';
					echo "<div id='center'><p>Vous n'êtes pas autorisé à voir cette page.</p>";
					echo '<a href="shop.php">Retour</a>';
					echo '</div></div></div>';
					exit;
				}
			
			}elseif($_GET['article'] == 'send_new'){
				if(!empty($_SESSION['pseudo']) && $_SESSION['level'] == 0){ // Si l'utilisateur est bien un administrateur

					$admin_article->sendArticle(); // On envoit l'article sur la base de donnée
				
				}else{ // Si l'utilisateur n'est pas un administrateur on renvois une érreur
					echo '<div id="annonce"><div id="bordercenter">';
					echo "<div id='center'><p>Vous n'êtes pas autorisé à voir cette page.</p>";
					echo '<a href="shop.php">Retour</a>';
					echo '</div></div></div>';
					exit;
				}

			}elseif($_GET['article'] == 'mod' && !empty($_GET['id'])){
				if(!empty($_SESSION['pseudo']) && $_SESSION['level'] == 0){ // Si l'utilisateur est bien un administrateur

					$admin_article->modArticle(); // On envoit l'article sur la base de donnée
				
				}else{ // Si l'utilisateur n'est pas un administrateur on renvois une érreur
					echo '<div id="annonce"><div id="bordercenter">';
					echo "<div id='center'><p>Vous n'êtes pas autorisé à voir cette page.</p>";
					echo '<a href="shop.php">Retour</a>';
					echo '</div></div></div>';
					exit;
				}

			}elseif($_GET['article'] == 'update' && !empty($_GET['id'])){
				if(!empty($_SESSION['pseudo']) && $_SESSION['level'] == 0){ // Si l'utilisateur est bien un administrateur

					$admin_article->updateArticle($_GET['id']); // On envoit l'article sur la base de donnée
				
				}else{ // Si l'utilisateur n'est pas un administrateur on renvois une érreur
					echo '<div id="annonce"><div id="bordercenter">';
					echo "<div id='center'><p>Vous n'êtes pas autorisé à voir cette page.</p>";
					echo '<a href="shop.php">Retour</a>';
					echo '</div></div></div>';
					exit;
				}
			}
		?>
    </body>
	
	<!-- bas du site. -->
	<footer>
		<br/><br/>© 2020 Delta Project
	</footer>
</html>
