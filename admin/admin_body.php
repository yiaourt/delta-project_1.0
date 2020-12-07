<?php // Nous coderons ici la page Panneaux administrateurs
// On commence par vérifier si l'utilisateur est connecter.

// Donc, si la variable session pseudo est vide alors on demande à l'utilisateur d'être connecter.
if(empty($_SESSION['pseudo'])){
	echo '<div id="annonce"><div id="bordercenter">';
	echo "<div id='center'><p>Vous n'êtes pas autorisé à voir cette page.</p>";
	echo '<a href="home.php">Retour</a>';
	echo '</div></div></div>';
	exit;
}else{ // Sinon,
	// Si l'utilisateur n'est pas administrateur
	if($_SESSION['level'] == 1){
		echo '<div id="annonce"><div id="bordercenter">';
		echo "<div id='center'><p>Vous n'êtes pas autorisé à voir cette page.</p>";
		echo '<a href="home.php">Retour</a>';
		echo '</div></div></div>';
		exit;
	}else{ // Sinon tout va bien l'administrateurs peut accéder au pannel-admin
		
		echo '<article>';
		echo '<div id="menumarg">';

		echo '<fieldset><legend>Panneau administrateur :</legend>';
		echo '<div id="menu">';
				echo "<label class=\"adminNav\"><a href='panel_admin.php?menu=user'>Gérer les utilisateurs</a></label>";
				echo "<label class=\"adminNav\"><a href='panel_admin.php?menu=topic'>Gérer le forum et la page d'accueil</a></label>";
				echo "<label class=\"adminNav\"><a href='panel_admin.php?menu=commande'>Gestionnaire de commandes</a></label>";
				echo "<label class=\"adminNav\"><a href='panel_admin.php?menu=param'>Paramètres du site</a></label>";
		echo '</div>';
		echo '</legend>';

		echo '</div>';
		echo '</article>';
	}
}
	
	
?>