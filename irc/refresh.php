 <?php 
// On se connecte à sql PDO
require($_SERVER['DOCUMENT_ROOT'].'/inc/func/func_connectsql.php');
$bdd = connectSql();

// on récupère les 10 derniers messages postés
$rq_tchat = $bdd->query('SELECT * FROM tchat');

while($sql_tchat = $rq_tchat->fetch()){
    // on affiche le message (l'id servira plus tard)
    echo "<div id='boxmsg'><div id='userofmsg'>" . $sql_tchat['user'] . "</div> : <div id='msg'>" . $sql_tchat['message'] . "</div></div>";
}

$rq_tchat->closeCursor();

?>