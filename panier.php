<?php session_start(); ?>
<?php require('inc/head_head.php'); ?>

	<!-- tête du site -->
	<header>
	<?php // On déclare ou est l'utilisateur sur le site
		$_SESSION['WIU'] = 'messagerie';

		require("inc/header_menu.php"); ?>
	</header>
	<!-- tête du site -->
	
	<!-- corps du site -->
    <body>
	<?php

		// On appelle la barre de menu au top qui s'anime avec scrollMagic. 
		$nav->nav_barre_top(); //(fonction qui fait parti de la classe "menu_navigation.php")
		
		// On vérifie que l'utilisateur est bien connecter
		if(empty($_SESSION['pseudo'])){
			echo '<br/>';
			echo '<div id="menumarg">';
			echo '<div id="center">';
			echo 'Vous devez être connecter pour passer une commande, <a href="inscription.php">inscrivez-vous</a> ou <a href="connexion.php">Connectez-vous</a>';
			echo '</div></div>';
			exit;
		}

		// On appelle les classes du panier qui se chargeront de vérifier les données de l'utilisateur
		// d'afficher la page de paiment, puis enfin d'envoyer les données sur la bdd 
		// afin que les administrateurs puissent afficher la commande et son état actuelle.
		
		require('panier/class_panier.php');
		$class_panier = new panier; // On commence par récuperer la classe verify


		// Ci dessous, l'utilisateur est connecter...
		//--------------------------------------------------------------------------------------
		if(!empty($_GET['history']) && $_GET['history'] == "1"){
			// Si l'utilisateur est sur l'historique de ces commandes.
			
			$class_panier->history();

			exit;

		}elseif(!empty($_GET['success']) && $_GET['success'] == "1"){
			// Si l'utilisateur se trouve avoir terminer le paiement de sa commande panier.

			$class_panier->finish_transaction();


		}elseif(!empty($_GET['delete']) && $_GET['delete'] == '1'){ 
			// On vérifie si l'utilisateur veut suprimer toutes ces commandes ou un article.

			if(!empty($_GET['id'])){ // Si l'utilisateur veut enlever un article.

				unset($_SESSION['panier'][array_search($_GET['id'], $_SESSION['panier'])]);

				sort($_SESSION['panier']);

			}else{ // Sinon l'utilisateur veut vider son panier.

				unset($_SESSION['panier']); // On réinitialise le panier

				$_SESSION['panier'] = array();

				// On réinitialise le panier dans la bdd
				$req_panier_user = $bdd->prepare('UPDATE user SET panier = "" WHERE username = :pseudo');

				$req_panier_user->execute(array(

					'pseudo' => $_SESSION['pseudo']
				));

				$req_panier_user->closeCursor();

				// On initialize ensuite la variable session total_panier
				$_SESSION['total_panier'] = 0;

				foreach($_SESSION['panier'] as $id){ // Puis on compte les éléments du panier dans une boucle.

					$_SESSION['total_panier'] = $_SESSION['total_panier'] + 1;

				}
			}
		
		}elseif(!empty($_GET['save']) && $_GET['save'] == '1'){ 
		// si l'utilisateur à cliquer sur "terminer la commande !" on l'envois sur une vérification de ses P.I.

			// Puis on conditionne toutes les pages du formulaire d'informations personnel...
			/////////////////////////////////////////

			if(!empty($_GET['sendpi'])){
				// Ici l'utilisateur souhaite envoyer ces informations personnelle (pi = personnal informations)

				$class_panier->sendPI();

			}else{ // sinon sendpi n'existe pas

				if(!empty($_GET['mod'])){ // Si l'utilisateur souhaite changer ces informations personnel

					$class_panier->modPi();

				}else{ // Sinon, on est sur la page de vérification. (panier.php?save=1)

					if(!empty($_GET['sendtopaypal'])){ 
					// Si l'utilisateur reconnait ses informations personnel et souhaite passer au paiement de la commande.

						$class_panier->startTransaction();


					}else{ // Sinon on est simplement dans panier.php?save=1

						$class_panier->verifyUser();
					}

				}

			}

			exit;
		}

		// On créer l'affichage des articles du panier de l'utilisateur.
		echo '<article>';

		echo '<div id="menumarg">';
		echo '<h2><u>Mon Panier :</u></h2>';

		// un bouton qui affiche la page des historiques de commandes
		echo '<div id="button_history_commande"><a href="panier.php?history=1">Voir l\'historique des mes commandes</a></div><br/>';

		echo '<table id="viewer_init">';

		echo '<tr>';
	    echo '<th>Image</th>';
	    echo '<th>Titre de l\'article</th>';
	    echo '<th>Prix</th>';
   		echo '</tr>';

   		// On vérifie qu'il y à des article dans le panier
   		if(!empty($_SESSION['panier'])){ // Si articles, il y à

   			// On compte le nombre d'articles
	   		$count = 0;

			// On affiche chaques articles du panier 1 à 1 dans une boucles
			foreach($_SESSION['panier'] as $nbr => $id){

				// On incrémente count
				$count = $count+1;

			    $req_panier = $bdd->prepare('SELECT * FROM shop WHERE id=:id');

			    $sql_panier = $req_panier->execute(array(
			    	'id' => $id
			    ));

			    while($sql_panier = $req_panier->fetch()){
			    	echo '<tr>

			    	<td><img id="image_item_panier" src="'.$sql_panier['image1'].'" alt="'.$sql_panier['titre'].'"/></td>

			    	<td>+1 '.$sql_panier['titre'].'</td>

			    	<td>'.$sql_panier['prix'].' €</td>

			    	<td><a href="panier.php?delete=1&id='.$sql_panier['id'].'">x </a></td>

			    	</tr>';

			    	//On met également le prix dans une variable array.
			    	$multi_prix[] = $sql_panier['prix'];
			    }
			    $req_panier->closeCursor();
			}
			echo '</table>';

			// On calcul le prix total
			foreach($multi_prix as $prix){

				if(!empty($first)){

					$total = $total + $prix;
				
				}else{

					$total = $prix;

					$first = 'ok';
				}
			}

			// Un bouton qui vide entiérement le panier
			echo '<a id="floatright" href="panier.php?delete=1">Vider le panier</a>';

			// On affiche le total des commandes
			echo '<h3>'.$count.' Article(s) pour un total de : <b>'.$total.' €</b></h3>';

			echo '<a id="button_end_panier" href="panier.php?save=1">Terminer la commande !</a>';

   		}else{ // Sinon, si il n'existe aucun article dans le panier ...

   			echo '</table>';

   			// On affiche un message disant qu'il ni à pas d'articles dans le panier
			echo '<h3>Vous n\'avez pas encore d\'articles dans le panier, rendez-vous dans notre <a href="shop.php">boutique d\'objets 3D</a> pour commencer vos achats.</b></h3>';

   		}

   		echo '</div>';
		
		echo '</article>';

		echo '<script src="panier/js/panier.js"></script>';

		
		
	?>

    </body>
	<!-- (fin) corps du site -->
	
	<!-- bas du site. -->
	<footer>
		<br/><br/>© 2020 Delta Project
	</footer>
</html>