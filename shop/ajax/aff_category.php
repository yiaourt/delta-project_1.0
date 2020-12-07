// On créer une catégorie qui affiche toutes les catégories.
		echo '<div id="shop_category"><a href="shop.php">Toutes les catégories</a></div>';

		$reqshop_cat = $bdd->query('SELECT * FROM shop_category');

		while($sqlshop_cat = $reqshop_cat->fetch()){
			echo '<div id="shop_category"><a href="javascript:void(0)" onclick="listCategory('.$sqlshop_cat['id'].')">'.$sqlshop_cat['name'].'</a></div>';
		}
		$reqshop_cat->closeCursor();