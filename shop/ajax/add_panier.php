<?php session_start();

require($_SERVER['DOCUMENT_ROOT'].'/inc/func/func_connectsql.php');
$bdd = connectSql();

// Le fichier ci dessous sert à ajouter au panier un article lorsque le bouton ajouter au panier est cliquer.
// -----------------------------------------------------------------------------------------------------------

// On compte le total_panier
$_SESSION['total_panier'] = 0;

foreach($_SESSION['panier'] as $id){ // Puis on compte les éléments du panier dans une boucle.

	$_SESSION['total_panier'] = $_SESSION['total_panier'] + 1;

}

// On vérifie si l'utiliasteur dépasse 15 articles ..
if($_SESSION['total_panier'] >= 15){ 


	exit;

}else{ // Si on ne dépasse pas 15 articles on envois l'article au panier.

	// On déclare l'id de l'article récuperer
	$id = $_POST['id'];

	// On récupère le titre dans la base de données.
	$req_titre_article = $bdd->prepare('SELECT titre FROM shop WHERE id = :id');

	$sql_titre_article = $req_titre_article->execute(array(
		'id' => $id
	));

	while($sql_titre_article = $req_titre_article->fetch()){
		$titre = $sql_titre_article['titre'];
	}

	$req_titre_article->closeCursor();

	// Puis on ajoute l'id de l'article au tableau panier du client
	$_SESSION['panier'][] = $id;

	if(!empty($_SESSION['pseudo'])){

		// On serialize pour mettre le tableau sur la bdd
		$serialized_panier = serialize($_SESSION['panier']);
		
		// On envois les données dans sa collone panier sur la bdd
		$req_panier_user = $bdd->prepare('UPDATE user SET panier = :serializedPanier WHERE username = :pseudo');

		$req_panier_user->execute(array(
			
			'serializedPanier' => $serialized_panier,
			'pseudo' => $_SESSION['pseudo']
		));

		$req_panier_user->closeCursor();

		// On initialize ensuite la variable session total_panier
		$_SESSION['total_panier'] = 0;

		foreach($_SESSION['panier'] as $id){ // Puis on compte les éléments du panier dans une boucle.

			$_SESSION['total_panier'] = $_SESSION['total_panier'] + 1;

		}
	}
}



?>