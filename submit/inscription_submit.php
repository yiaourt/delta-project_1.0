<?php session_start();
	
// On se connecte à sql PDO
require('../inc/func/func_connectsql.php');
$bdd = connectSql();

// On récupere l'adresse ip de l'utilisateur en appelant la fonctions get_ip
require('../inc/func/func_get_ip.php');

// On vérifie s'il y a des érreurs en déclarant la variable error
$error = 0;

// On vérifie que le formulaire n'est pas vide :
if(empty($_POST['username'])){
	$error = 1;
}

if(empty($_POST['mdp1'])){
	$error = 1;
}

if(empty($_POST['mdp2'])){
	$error = 1;
}

if(empty($_POST['mail'])){
	$error = 1;
}

if($error == 1){
	header ("Location: ../inscription.php?error=1" );
	exit;
}

// ici on vérifie que les 2 mot de passes rentrer par l'utilisateur sont identiques et on les chiffres dans une variable
if($_POST['mdp1'] == $_POST['mdp2']){
	// On hash le mdp
	$mdp = password_hash($_POST['mdp1'], PASSWORD_DEFAULT);
}else{
	$error = 2;
}

if($error == 2){
	header ("Location: ../inscription.php?error=2" );
	exit;
}

// On vérifie ici que le pseudo rentrer est conforme.
$username = $_POST['username'];
if(preg_match("#[^a-zA-Z0-9_]#", $username)){
	header ("Location: ../inscription.php?error=3" );
	exit;
}

// On vérifie ensuite que le pseudo n'existe pas déjà.
$req = $bdd->prepare("SELECT id FROM user WHERE username = :postuser");
$req->execute(array(
		'postuser' => $username
));
$resultat = $req->fetch();

if(!empty($resultat['id'])){ // Si la variable n'est pas vide c'est que le pseudonyme existe.
	header ("Location: ../inscription.php?error=4" );
	$req->closeCursor();
	exit;
}

// On vérifie maintenant que le mail est conforme
$mail = htmlspecialchars($_POST['mail']);
if(!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $mail)){
	header ("Location: ../inscription.php?error=5" );
	exit;
}

// On vérifie si le formulaire d'informations personnelle est remplis.
if(!empty($_POST['sexe']) OR 
!empty($_POST['prenom']) OR 
!empty($_POST['nom']) OR 
!empty($_POST['adresse']) OR 
!empty($_POST['ville']) OR
!empty($_POST['postal'])){

	// On vérifie maintenant qu'il ni à pas de cases vide...
	if(empty($_POST['sexe']) OR 
	empty($_POST['prenom']) OR 
	empty($_POST['nom']) OR 
	empty($_POST['adresse']) OR 
	empty($_POST['ville']) OR
	empty($_POST['postal'])){

		// Si il y à une informations personnelle de rentrer et que l'une d'entre elle n'est pas déclarer.
		// Il y à donc une érreur, on renvois l'utilisateur.
		header ("Location: ../inscription.php?error=6" );
		exit;
	}

}

// On vérifie enfin que la case CGU est bien cocher.
if(!isset($_POST['CGU'])){
	
	header ("Location: ../inscription.php?error=7" );
	exit;
}

if($error == 0){
	// Sinon si il ni à pas d'érreurs on envoi les données sur la base de donnée puis on connecte le nouvel utilisateur.
	
	// On initialise certaines variables utile au profil...

	$img_profil = "<img src='img/basic_img_profil.png' alt='Image de Profil' align='middle'>"; // une image de profil par défaut

	$icone_img_profil = "<img id='icone' src='img/basic_img_profil.png' alt='Image de Profil' align='middle'>"; // Une icone de l'image de profil

	// On fait ici un réinitialisateur de variables pour les notifications de nouveaux topics.
	$_SESSION['lastdateTP'] = array();
	//-----------------------------------------------------------------------
	// J'initialise "id_s_f => dernière date du dernier topic" dans un tableau et une variable session pour pouvoir notifier l'utilisateur de nouveau topic par la suite sur le forum.
	// Je commence par récuperer chaques "id" de chaques sous-catégories dans la base de données.
	$reqidsf = $bdd->query('SELECT id FROM sous_forum');
	
	while($sqlidsf = $reqidsf->fetch()){
		$idsf = $sqlidsf['id'];

		// Je fais ici une requête pour récuperer la date du dernier message du sous-forum afin d'initialiser cette valeur à un tableau qui sera stocker dans une collone utilisateur.
		$reqlastdatesf = $bdd->prepare('SELECT date FROM sous_forum_topic WHERE id_s_cat = :sqlidsf ORDER BY date DESC');
		$sqllastdatesf = $reqlastdatesf->execute(array('sqlidsf' => $idsf));
		
		$sqllastdatesf = $reqlastdatesf->fetch();
			
		if(!empty($sqllastdatesf['date'])) { // Si il y à une date de topic..
			$idsflastdate = $sqllastdatesf['date'];
		}else{ // Si aucune date existe..
			$idsflastdate = '0';
		}
		$reqlastdatesf->closeCursor();
		
		// je réinitialise ensuite la variable $_SESSION pour avoir en mémoire chaques dernières dates des topics du forum.
		$_SESSION['lastdateTP'][$idsf] = $idsflastdate;
	}
	$reqidsf->closeCursor();



	// On fait ici un réinitialisateur de variables pour les notifications de nouveaux comentaires.
	//-----------------------------------------------------------------------
	$_SESSION['lastdateCM'] = array();
	// Je cherche maintenant à créer la variable "$_SESSION['lastdateCM']" contenant les dernières dates de chaques commentaires associer au topic du forum.
	// Je fais pour cela une requête de chaques id de topics.
	//-----------------------------------------------------------------------
	$reqidtop = $bdd->query('SELECT id, date FROM sous_forum_topic');
	
	while($sqlidtop = $reqidtop->fetch()) {
		$idTP = $sqlidtop['id'];
		$dateTP = $sqlidtop['date'];
		
		$reqlastdatecom = $bdd->prepare('SELECT date FROM topic_comment WHERE topic_id = :idtp ORDER BY date DESC');
		$sqllastdatecom = $reqlastdatecom->execute(array('idtp' => $idTP));
		
		$sqllastdatecom = $reqlastdatecom->fetch();
			
		if(!empty($sqllastdatecom['date'])) { // Si il y à la date d'un commentaire
			$idtplastdate = $sqllastdatecom['date'];
		}else{ // Si aucune date existe..
			$idtplastdate = $dateTP; // Pour les commentaires on initialise la date du topic plutôt qu'un 0 afin d'être sure qu'il n'y ai pas de bug avec le premier nouveau commentaire.
		}
		$reqlastdatecom->closeCursor();
		
		// je réinitialise ensuite la variable $_SESSION pour avoir en mémoire chaques dernières dates des topics du forum.
		$_SESSION['lastdateCM'][$idTP] = $idtplastdate;
		
	} // On ferme la boucle.
	$reqidtop->closeCursor();
	// --------------------------------------------------------------------------------
	// J'utilise ensuite serialize() pour convertir les tableaux php en un tableau sql.
	$serializedvarTP = serialize($_SESSION['lastdateTP']);
	$serializedvarCM = serialize($_SESSION['lastdateCM']);

	// On vérifie le panier $_SESSION['panier]
	if(!empty($_SESSION['panier'])){
		
		$serialized_panier = serialize($_SESSION['panier']);

		// On calcule ensuite le total d'articles dans le panier.
		$_SESSION['total_panier'] = 0;
		
		foreach($_SESSION['panier'] as $id){

			// On incrémente le nombre d'articles.
			$_SESSION['total_panier'] = $_SESSION['total_panier'] + 1;

		}

	}else{

		$serialized_panier = '';

		$_SESSION['total_panier'] = 0;
	}

	// On initialise les variables d'informations personnel
	if(!empty($_POST['sexe']) OR 
	!empty($_POST['prenom']) OR 
	!empty($_POST['nom']) OR 
	!empty($_POST['adresse']) OR 
	!empty($_POST['ville']) OR
	!empty($_POST['postal'])){

		if(empty($_POST['sexe']) OR 
		empty($_POST['prenom']) OR 
		empty($_POST['nom']) OR 
		empty($_POST['adresse']) OR 
		empty($_POST['ville']) OR
		empty($_POST['postal'])){ // Si le formulaire est vide

			// On déclare toutes les variables vide
			$sexe = '';
			$prenom = '';
			$nom = '';
			$adresse = '';
			$ville = '';
			$postal = '';
		
		}else{ // Sinon le formulaire d'informations personnel existe bien.

			// On initialise donc toutes les variables d'informations personnel au formulaire envoyer
			$sexe = $_POST['sexe'];
			$prenom = $_POST['prenom'];
			$nom = $_POST['nom'];
			$adresse = $_POST['adresse'];
			$ville = $_POST['ville'];
			$postal = $_POST['postal'];
		}

	}
	
	// on écrit ensuite les données du nouvelle utilisateur sur la base de données à la table "user" 
	$reqinsuser = $bdd->prepare('INSERT INTO 
		user(username, pass, mail, level, img_profil, icone_img_profil, ip, date_inscription, lastdat_TP, lastdat_CM, is_connect, panier, sexe, prenom, nom, adresse, ville, postal) 
	
	VALUES(:username, :pass, :mail, "1", :img_profil, :icone_img_profil, :getip, :thisdate, :serializedvarTP, :serializedvarCM, "0", :panier, :sexe, :prenom, :nom, :adresse, :ville, :postal)');
	
	$reqinsuser->execute(array(

		'username' => $_POST['username'],

		'pass' => $mdp,

		'mail' => $_POST['mail'],
		
		'img_profil' => $img_profil,

		'icone_img_profil' => $icone_img_profil,
		
		'getip' => get_ip(),
		
		'thisdate' => date('ymdhi'),
		
		'serializedvarTP' => $serializedvarTP,
		
		'serializedvarCM' => $serializedvarCM,

		'panier' => $serialized_panier,

		'sexe' => $sexe,

		'prenom' => $prenom,

		'nom' => $nom,

		'adresse' => $adresse,

		'ville' => $ville,

		'postal' => $postal
		
	));
	$reqinsuser->closeCursor();
	
	// On à besoin de récuperer l'id qui n'est à jour que maintenant dans la base de données.
	$reqiduser = $bdd->prepare('SELECT id FROM user WHERE username=:postuser'); 
	$sqliduser = $reqiduser->execute(array('postuser' => $_POST['username']));
	
	while($sqliduser = $reqiduser->fetch()) {
		$_SESSION['id'] = $sqliduser['id'];
	}
	$reqiduser->closeCursor();

	// On déclare ici les variables session pour connecter l'utilisateur directement aprés l'inscription.
	//--------------------------------------------------------------------------------
	// /!\ l'id ne peut être déclarer aprés la création de l'utilisateur.
	
	$_SESSION['pseudo'] = $_POST['username'];
	$_SESSION['mail'] = $_POST['mail'];
	$_SESSION['level'] = 1;
	$_SESSION['img_profil'] = $img_profil;
	$_SESSION['icone_img_profil'] = $icone_img_profil;
	$_SESSION['preview_home'] = '';
	$_SESSION['preview_home_title'] = '';
	$_SESSION['lastmsg'] = date('ymdhi')-03;
	$_SESSION['ip'] = get_ip();
	
	// on redirige l'utilisateur avec return 1 qui contient le texte html : inscription réussie.
	echo '<script language="Javascript">document.location.replace("../connexion.php?return=1");</script>';
	exit;
	
}

?>