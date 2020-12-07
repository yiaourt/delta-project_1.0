<?php session_start();

require('inc/func/func_connectsql.php');
$bdd = connectSql();

$style = $_SESSION['style']; // On récupere les données session du thèmes

// On déconnecte l'utilisateur de la base de données également pour que il ne soit plus afficher en ligne.
if(!empty($_SESSION['pseudo'])){
	$req_disconnect = $bdd->prepare('UPDATE user SET is_connect = 0 WHERE username = :pseudo');

	$req_disconnect->execute(array(
		'pseudo' => $_SESSION['pseudo']
	));

	$req_disconnect->closeCursor();
}

session_destroy(); // On détruit les données session

header('location: index.php?theme='.$style.''); // On renvois l'utilisateur déconnectés avec le thème par défault
exit;

?>