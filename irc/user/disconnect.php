<?php session_start();
// On se connecte à sql PDO
require($_SERVER['DOCUMENT_ROOT'].'/inc/func/func_connectsql.php');
$bdd = connectSql();


// On déconnecte l'utilisateur en vérifiant qu'il est bien connecter au variables $_SESSION
if(!empty($_SESSION['pseudo'])){
	$req_disconnect = $bdd->prepare('UPDATE user SET is_connect = 0 WHERE username = :pseudo');

	$req_disconnect->execute(array(
		'pseudo' => $_SESSION['pseudo']
	));

	$req_disconnect->closeCursor();
}


?>