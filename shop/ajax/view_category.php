<?php session_start();

	// Ce fichier permet de charger les catégories par leurs boutons respectivement en haut du shop.

	require($_SERVER['DOCUMENT_ROOT'].'/inc/func/func_connectsql.php');
	$bdd = connectSql();

	// Ci dessous, j'affiche les articles du shop
		$reqshop = $bdd->query('SELECT * FROM shop');

		while($sqlshop = $reqshop->fetch()){

			echo '<div id="item_shop">'.$sqlshop['titre'].'<br/><a href="javascript:void(0)" id="'.$sqlshop['id'].'" onclick="chargeArticle('.$sqlshop['id'].')">

			<img id="img_shop" src="'.$sqlshop['image1'].'" alt="'.$sqlshop['titre'].'" /></a>
			
			<br/>'.$sqlshop['prix'].' €</div>';
		}
		$reqshop->closeCursor();

?>