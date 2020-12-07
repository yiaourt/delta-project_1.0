<?php class aff_shop {
	
	public function afficheShop(){
		$bdd = connectSql();

		echo '<article>';
		// Ci dessous on affiche le shop chaques articles en grille et les catégories
		echo '<h2>Shop 3D : </h2>';

		echo '<hr/>';
		
		echo '<div id="sticky_block">'; // Je créer un bloc sticky
		//Ci dessous, j'affiche les catégories du shop
		echo '<div id="category">';

		// J'ajoute 2 icones sur un bouton javascript.
		echo '<a id="list_button" href="javascript:void(0)" onclick="listView()"><img id="img_list" src="img/list.png" alt="list.png" /></a>';

		echo '<a id="list_button" href="javascript:void(0)" onclick="unlistView()"><img id="img_unlist" src="img/unlist.png" alt="unlist.png" /></a>';

		// On créer une catégorie qui affiche toutes les catégories.
		echo '<span id="shop_category"><a href="javascript:void(0)" onclick="listCategory(0)">Toutes les catégories</a></span>';

		$reqshop_cat = $bdd->query('SELECT * FROM shop_category');

		while($sqlshop_cat = $reqshop_cat->fetch()){
			echo '<span id="shop_category"><a href="javascript:void(0)" onclick="listCategory('.$sqlshop_cat['id'].')">'.$sqlshop_cat['name'].'</a></span>';
		}
		$reqshop_cat->closeCursor();
		echo '</div>';

		echo '</div>'; // <div id="sticky_block">

		echo '<article>';
		echo '<div id="shop">';

		// Ci dessous, j'affiche les articles du shop
		$reqshop = $bdd->query('SELECT * FROM shop');

		while($sqlshop = $reqshop->fetch()){

			echo '<div id="item_shop">'.$sqlshop['titre'].'<br/><a href="javascript:void(0)" id="'.$sqlshop['id'].'" onclick="chargeArticle('.$sqlshop['id'].')">

			<img id="img_shop" src="'.$sqlshop['image1'].'" alt="'.$sqlshop['titre'].'" /></a>
			
			<br/>'.$sqlshop['prix'].' €</div>';
		}
		$reqshop->closeCursor();
		
		echo '</div>';
		// --------------------------------------------------------

		// Ci dessous, on affiche l'article avec javascript/jquery
		echo '<div id="article">';
		echo '</div>';
		// --------------------------------------------------------

		// On fait un bouton pour créer de nouveaux articles et de nouvelles catégories dans la base de données par l'administrateur !
		if(!empty($_SESSION['pseudo']) && $_SESSION['level'] == 0) {
			echo '<div id="adminButtonShop"><a href="shop.php?article=new">Ajouter un nouvelle article</a></div>';
			echo '<div id="adminButtonShop"><a href="shop.php?article=new_category">Ajouter une nouvelle catégorie</a></div>';
		}

		// Et on script le shop.
		echo '<script src="shop/js/shop.js"></script>';
		
	}


}

?>