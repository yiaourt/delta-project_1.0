<?php session_start();

	// Ce fichier permet de charger les catégories par leurs boutons respectivement en haut du shop.

	require($_SERVER['DOCUMENT_ROOT'].'/inc/func/func_connectsql.php');
	$bdd = connectSql();

	if($_POST['id'] == '0'){
		// Ci dessous, j'affiche toutes les catégories
		$reqshop_cat = $bdd->query('SELECT * FROM shop');

		while($sqlshop_cat = $reqshop_cat->fetch()){
				
			// Et on affiche l'article qui porte l'id de la catégorie voulu dans la collone 'category'.
			echo '<div id="item_shop">'.$sqlshop_cat['titre'].'<br/>

			<a href="#" id="'.$sqlshop_cat['id'].'" onclick="chargeArticle('.$sqlshop_cat['id'].')">

			<img id="img_shop" src="'.$sqlshop_cat['image1'].'" alt="'.$sqlshop_cat['titre'].'" /></a>
			
			<br/>'.$sqlshop_cat['prix'].' €</div>';
			
		}
		$reqshop_cat->closeCursor();
		
	}else{
		// Ci dessous, j'affiche les articles du shop et je les listes selon l'id de catégorie.
		$reqshop_cat = $bdd->query('SELECT * FROM shop');

		while($sqlshop_cat = $reqshop_cat->fetch()){

			// Donc on recherche dans la collone category l'id de la catégorie.
			if(preg_match('#'.$_POST['id'].'#', $sqlshop_cat['category'])){ 
				
				// Et on affiche l'article qui porte l'id de la catégorie voulu dans la collone 'category'.
				echo '<div id="item_shop">'.$sqlshop_cat['titre'].'<br/>

				<a href="javascript:void(0)" id="'.$sqlshop_cat['id'].'" onclick="chargeArticle('.$sqlshop_cat['id'].')">

				<img id="img_shop" src="'.$sqlshop_cat['image1'].'" alt="'.$sqlshop_cat['titre'].'" /></a>
				
				<br/>'.$sqlshop_cat['prix'].' €</div>';
			}
			
		}
		$reqshop_cat->closeCursor();
	}

?>