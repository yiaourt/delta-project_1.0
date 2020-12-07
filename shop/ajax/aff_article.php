<?php session_start();

require($_SERVER['DOCUMENT_ROOT'].'/inc/func/func_connectsql.php');
$bdd = connectSql();

// On déclare l'id
$id = $_POST['id'];

// On vérifie si l'utilisateur veut afficher +1 article ou -1 article

if(!empty($_POST['direction']) && $_POST['direction'] == 'moins'){ // Donc l'utilisateur ici renvois -1 article
	
	// On fait une petite vérification pour savoir si l'id envoyer existe bien.
	$verify = $bdd->prepare('SELECT * FROM shop WHERE id=:id');

	$sqlverify = $verify->execute(array(
		'id' => $id
	));

	while($sqlverify = $verify->fetch()){
		$titre = $sqlverify['titre'];
	}
	$verify->closeCursor();

	if(empty($titre)){
		$id = $id+1; // Donc si titre n'existe pas on rajoute +1 à l'id pour ne pas avoir d'érreur.
	}
}

if(!empty($_POST['direction']) && $_POST['direction'] == 'plus'){ // Donc l'utilisateur ici renvois +1 article
	
	// On fait une petite vérification pour savoir si l'id envoyer existe bien.
	$verify = $bdd->prepare('SELECT * FROM shop WHERE id=:id');

	$sqlverify = $verify->execute(array(
		'id' => $id
	));

	while($sqlverify = $verify->fetch()){
		$titre = $sqlverify['titre'];
	}
	$verify->closeCursor();

	if(empty($titre)){
		$id = $id-1; // Donc si titre n'existe pas on rajoute -1 à l'id pour ne pas avoir d'érreur.
	}
}

// On affiche ensuite l'article, on lance d'abord une requête.
$reqshop2 = $bdd->prepare('SELECT * FROM shop WHERE id=:id');

$sqlshop2 = $reqshop2->execute(array(
	'id' => $id
));

while($sqlshop2 = $reqshop2->fetch()){ // Ici on récupere l'articles du shop selon l'id de l'article demander puis on l'affiche.

	// On fait un lien de retour à la galerie
	echo '<a id="bordertopleftbutton" href="javascript:void(0)" onclick="dechargeArticle()"><- Retour à la galerie (ECHAP)</a>';
	
	// On fait un bouton modifier l'article pour les administrateurs
	if(!empty($_SESSION['pseudo']) && $_SESSION['level'] == 0) {
		echo '<p id="article_adminButton"><a href="shop.php?article=mod&id='.$sqlshop2['id'].'">Modifier l\'article -></a></p>';
	}
	
	echo '<hr/>'; // Ligne horyzontal 

	echo '<a href="javascript:void(0)" onclick="articlePrec('.$sqlshop2['id'].')" id="article_left_button"><-</a>';
	echo '<a href="javascript:void(0)" onclick="articleSuiv('.$sqlshop2['id'].')" id="article_right_button">-></a>';

	echo '<div id="article_flex">';

	echo '<div id="conteneur_img_article">';
	echo '<img id="img_article" src="'.$sqlshop2['image1'].'" alt="'.$sqlshop2['titre'].'" />';
	echo '</div>';

	echo '<div id="flex_img_article">';
	if(!empty($sqlshop2['image1'])){
		echo '<a href="javascript:void(0)" onclick="chargeImage1('.$sqlshop2['id'].')"><img id="icone_img_article" src="'.$sqlshop2['image1'].'" alt="'.$sqlshop2['titre'].'" /></a>';
	}

	if(!empty($sqlshop2['image2'])){
		echo '<a href="javascript:void(0)" onclick="chargeImage2('.$sqlshop2['id'].')"><img id="icone_img_article" src="'.$sqlshop2['image2'].'" alt="'.$sqlshop2['titre'].'" /></a>';
	}
	if(!empty($sqlshop2['image3'])){
		echo '<a href="javascript:void(0)" onclick="chargeImage3('.$sqlshop2['id'].')"><img id="icone_img_article" src="'.$sqlshop2['image3'].'" alt="'.$sqlshop2['titre'].'" /></a>';
	}
	if(!empty($sqlshop2['image4'])){
		echo '<a href="javascript:void(0)" onclick="chargeImage4('.$sqlshop2['id'].')"><img id="icone_img_article" src="'.$sqlshop2['image4'].'" alt="'.$sqlshop2['titre'].'" /></a>';
	}
	if(!empty($sqlshop2['image5'])){
		echo '<a href="javascript:void(0)" onclick="chargeImage5('.$sqlshop2['id'].')"><img id="icone_img_article" src="'.$sqlshop2['image5'].'" alt="'.$sqlshop2['titre'].'" /></a>';
	}

	echo '</div>'; // <div id="flex_img_article">

	echo '<br/><hr/>';

	// On rend le titre htmlspecialchars
	$titrexhtml = str_replace("'", "\'", $sqlshop2['titre']);

	// On ajoute le titre, la description, le prix et un bouton ajouter au panier.
	echo '<div id="article_text"><h1>'.$sqlshop2['titre'].'</h1> <hr/>

			'.$sqlshop2['description'].' <hr/>

			<br/> <br/> <br/> <h2 id="article_prix"><u>Prix : '.$sqlshop2['prix'].' €</u></h2>
			
			
			<a id="buttonPanier" href="javascript:void(0)" onclick="addPanier('.$sqlshop2['id'].', \''.$titrexhtml.'\')">Ajouter au  &#128722;</a>
		</div>';

		echo '<div id="total_hidden_panier"></div>';

	echo '<hr/>';

	echo '</div>';

}
$reqshop2->closeCursor();

// On déclare l'id de l'article dans un script 
// (Permet entre autres d'initialiser la variable pour changer d'articles avec les flèches du clavier.)
echo '<script>
var id='.$id.';
</script>';

?>