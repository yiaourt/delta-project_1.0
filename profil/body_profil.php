<?php // Nous coderons ici la page de modification du profil de l'utilisateur connecter.
// On commence par vérifier si l'utilisateur est connecter.

// Donc, si la variable session pseudo est vide alors on demande à l'utilisateur d'être connecter.
if(empty($_SESSION['pseudo'])){
	echo '<div id="borderbgnomarg"><div id="bordercenter">';
	echo '<div id="center"><p>Vous devez être connecter pour afficher cette page.</p>';
	echo '<a href="connexion.php">Connectez-vous ...</a>';
	echo '</div></div></div>';
	exit;
}else{ // Sinon, On affiche la page de modification de profil pour l'utilisateur actuellement connecter
	
	// On met un titre
	echo '<div id="menumarg">';
	echo '<div id="bordernomarg"></div>';
	echo '<div class="titletext">Modifier mon profil :</div>';
	echo '<div id="border"></div>';
	
	// A partir d'ici je fais attention au div qui sont utiliser pour css
	echo '<div id="menumarg">';
	echo '<div class="flex_prfl">';
	echo '<div id="profil">';
	
	// On charge les classes ...
    // // // // // // // // // J'appelle chaque fonctions qui affichent les formulaires de modification du profil depuis la classe 'profil_mod'
	require('class/profil_mod.php');
	$profil_mod = new profil_mod;
	// // // // // // // // // Enfin, je les affichent ...
	
	////////////////
	// Le Pseudonyme
	$profil_mod->usrMod();
		
	////////////////////
	// L'image de profil
	$profil_mod->imgMod();
	
	echo '</div>'; //<div id="profil">';
	
	echo '<div id="nexttoprofil">';
	
	echo '<br/>';
	
	////////////////////
	// Le mot de passe
	$profil_mod->mdpMod();
	
	echo '<br/>';
	
	////////////////////
	// L'adresse e-mail
	$profil_mod->mailMod();
		
	echo '</div>';//<div id="nexttoprofil">';
	echo '</div>'; //<div class="flex_prfl">';
	echo '</div>'; //<div id="menumarg">'; La bordure autour noir
	
	// ici le bouton pour valider le formulaire
	if(!empty($_GET['mod']))
	{
		// ici on vérifie s'il y à une érreur de mot de passe
		if(empty($_GET['error'])){
			echo '<div id="information"><span class="underline">Information </span><span class="vert">:</span> Vous devez inscrire votre mot de passe pour pouvoir modifier votre profil</div>';
		}
		else{
			echo '<div id="annonce"><span class="underline">Attention </span><span class="vert">!</span> Vous devez inscrire votre mot de passe pour pouvoir modifier votre profil</div>';
		} 
		echo '<div id="center">';
		echo 'Mot de passe : ';
		echo '<input type="password" name="mdpp" /> ';
		echo '<input type="submit" value="Modifier" />';
		echo '</form>';
		echo '</div>';
	}
	
}
	
	
?>