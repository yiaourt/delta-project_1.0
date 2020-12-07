function refresh(){ 

    setTimeout( function(){
        
        // On charge le fichier qui rafraichis le panier
		$('#total_panier').load('shop/ajax/refresh_panier.php');

		// On charge le fichier qui rafraichis le panier de la barre des menu
		$('#total_nav_barre_panier').load('shop/ajax/refresh_panier.php');

		// On charge le fichier qui rafraichis le panier cacher
		$('#total_hidden_panier').load('shop/ajax/refresh_hidden_panier.php');

        refresh(); // On boucle la fonction

    }, 1000);

}

// On charge le fichier qui rafraichis le panier
$('#total_panier').load('shop/ajax/refresh_panier.php');

// On charge le fichier qui rafraichis le panier de la barre des menu
$('#total_nav_barre_panier').load('shop/ajax/refresh_panier.php');

// On charge le fichier qui rafraichis le panier cacher
$('#total_hidden_panier').load('shop/ajax/refresh_hidden_panier.php');

refresh(); // Et on boucle cela, toutes les 1 secondes.
