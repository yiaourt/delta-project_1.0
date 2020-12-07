<?php session_start();
// On se connecte à sql PDO
require($_SERVER['DOCUMENT_ROOT'].'/inc/func/func_connectsql.php');
$bdd = connectSql();


// On connecte l'utilisateur au tchat en vérifiant que il est bien connecter avec des variables $_SESSION
if(!empty($_SESSION['pseudo'])){
	$req_connect = $bdd->prepare('UPDATE user SET is_connect = 1 WHERE username = :pseudo');

	$req_connect->execute(array(
		'pseudo' => $_SESSION['pseudo']
	));

	$req_connect->closeCursor();
}

?>