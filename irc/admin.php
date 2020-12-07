<?php session_start();
	
require($_SERVER['DOCUMENT_ROOT'].'/inc/func/func_connectsql.php');
$bdd = connectSql();

	// On vérifie que c'est bien un admin qui veut administrer le tchat.
	if($_SESSION['level'] == 0 && !empty($_SESSION['pseudo'])){ 

		if($_GET['delete'] == 1){ // Si on veut supprimer le tchat...
			
			// set the PDO error mode to exception
			$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$sql = $bdd->prepare("DELETE FROM tchat");
			$sql->execute();
			$sql->closeCursor();

			// Puis on redirige l'administrateur ...
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("../irc.php#conversation")}, 500);</script>';
			exit;
		}
		

		// Puis on redirige l'administrateur ...
		echo '<script language="Javascript">setTimeout(function(){document.location.replace("../irc.php#conversation")}, 500);</script>';
		exit;

	}else{

		echo '<div id="borderbgnomarg"><div id="bordercenter">';
		echo "<div id='center'><p>Vous n'êtes pas autoriser à voir cette pâge.</p>";
		echo '<a href="/index.php">Retour</a>';
		echo '</div></div></div>';
		exit;

	}
?>
