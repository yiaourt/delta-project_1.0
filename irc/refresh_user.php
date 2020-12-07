 <?php 
// On se connecte à sql PDO
require($_SERVER['DOCUMENT_ROOT'].'/inc/func/func_connectsql.php');
$bdd = connectSql();

// Pour les utilisateurs en ligne on va simplement récuperer tous les pseudonyme quand is_connect = 1
$req_useronline = $bdd->query('SELECT username, level FROM user WHERE is_connect = "1"');

while($sql_useronline = $req_useronline->fetch()){

	if($sql_useronline['level'] == 0){ // On va afficher les administrateurs en rouge.
		echo '<span style="color: red;">';
	}

	echo $sql_useronline['username'].'<br/>';

	if($sql_useronline['level'] == 0){
		echo '</span>';
	}
}
$req_useronline->closeCursor();

?>