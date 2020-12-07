<?php session_start();

// On se connecte à sql PDO
require($_SERVER['DOCUMENT_ROOT'].'/inc/func/func_connectsql.php');
$bdd = connectSql();


if(!empty($_POST['message'])){ // si les variables ne sont pas vides

    $pseudo = $_SESSION['pseudo'];
    $message = htmlspecialchars($_POST['message']); // on sécurise nos données

    // puis on entre les données en base de données :
    $send_sql = $bdd->prepare('INSERT INTO tchat(user, message) VALUES(:user, :message)');
    $send_sql->execute(array(
        'user' => $pseudo,
        'message' => $message
    ));
    $send_sql->closeCursor();
}
else{
    echo "Vous avez oublié de remplir le message !";
}

?>
