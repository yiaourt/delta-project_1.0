<?php session_start();

require($_SERVER['DOCUMENT_ROOT'].'/inc/func/func_connectsql.php');
$bdd = connectSql();

$id = $_POST['id'];
$img = $_POST['image'];

$reqimgshop = $bdd->prepare('SELECT titre, '.$img.' FROM shop WHERE id=:id');

$sqlimgshop = $reqimgshop->execute(array(
	'id' => $id
));

while($sqlimgshop = $reqimgshop->fetch()){ // Ici on récupere l'articles du shop selon l'id de l'article demander puis on l'affiche.
	
	echo '<img id="img_article" src="'.$sqlimgshop[$img].'" alt="'.$sqlimgshop['titre'].'" />';

}
$reqimgshop->closeCursor();

?>