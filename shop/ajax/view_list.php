<?php session_start();

	// Ce fichier permet de charger les catégories par leurs boutons respectivement en haut du shop.

	require($_SERVER['DOCUMENT_ROOT'].'/inc/func/func_connectsql.php');
	$bdd = connectSql();

	// On créer le tableau en flexbox pour afficher les articles du forum sous formes de listes.
	echo '<div id="tableau_shop_row">';

	echo '<div id="tableau_shop_image">Image de l\'article</div>';
	echo '<div id="tableau_shop_description">Description</div>';
	echo '<div id="tableau_shop_prix">Prix</div>';
	
	echo '</div>';

	// Ci dessous, j'affiche les articles du shop
	$reqshop = $bdd->query('SELECT * FROM shop');

	while($sqlshop = $reqshop->fetch()){

		echo '<a href="javascript:void(0)" id="'.$sqlshop['id'].'" onclick="chargeArticle('.$sqlshop['id'].')">';

		echo '<div id="tableau_item_row">';

		echo '<div id="tableau_item_image"><img id="img_listview" src="'.$sqlshop['image1'].'" alt="'.$sqlshop['titre'].'" /></div>'; // l'image

		echo '<div id="tableau_item_description">'.$sqlshop['titre'].'</div>'; // La description

		echo '<div id="tableau_item_prix">'.$sqlshop['prix'].' €</div>'; // Le Prix

		echo '</div>';

		echo '</a>';
	}
	$reqshop->closeCursor();

?>