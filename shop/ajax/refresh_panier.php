<?php session_start();

require($_SERVER['DOCUMENT_ROOT'].'/inc/func/func_connectsql.php');
$bdd = connectSql();

// On initialize la variable session total_panier
$_SESSION['total_panier'] = 0;

if(empty($_SESSION['panier'])){

	$_SESSION['panier'] = array();
}

foreach($_SESSION['panier'] as $id => $item){ // Puis on compte les éléments du panier dans une boucle.

	$_SESSION['total_panier'] = $_SESSION['total_panier'] + 1;

}

echo $_SESSION['total_panier'];
?>