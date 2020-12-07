<?php
	
	$requsermod = $bdd->prepare('SELECT id, mail, username, level, img_profil FROM user WHERE id = :getuserid');

	$requsermod->execute(array(

	'getuserid' => $_GET['id']));

	$sql = $requsermod->fetch();
	$sql_username = $sql['username'];
	$sql_img_profil = $sql['img_profil'];
	$sql_id = $sql['id'];
	$sql_mail = $sql['mail'];
	$requsermod->closeCursor();
	// On met un titre
	echo '<div id="menumarg">';
	echo '<div id="bordernomarg"></div>';
	echo '<div class="titletext">Profil de '.$sql_username.'</div>';
	echo '<div id="border"></div>';
	
	// A partir d'ici je fais attention au div qui sont utiliser pour css
	echo '<div id="menumarg">';
	echo '<div class="flex_prfl">';
	echo '<div id="profil">';
	////////////////
	// Le Pseudonyme
	if(!empty($_GET['mod']) && $_GET['mod'] == 'usr')
	{
		echo $sql_username.' (<a href="panel_admin.php?menu=user&id='.$sql_id.'">Retour</a>)';
		echo '<form action="panel_admin.php?menu=user&id='.$sql_id.'&post=submit" method="post">';
		echo '<div id="block"><input type="text" name="usr" /></div>';
	}
	else
	{
		echo '<a href="panel_admin.php?menu=user&mod=usr&id='.$sql_id.'" class="titleuser">'.$sql_username.'</a>';
	}
		
	////////////////////
	// L'image de profil
	if(!empty($_GET['mod']) && $_GET['mod'] == 'img_profil')
	{
		echo '<div id="borderfleft">'.$sql_img_profil.'</div>';
		echo "(<a href='panel_admin.php?menu=user&id=".$sql_id."'>Retour</a>) ";
		echo '<form enctype="multipart/form-data" action="panel_admin.php?menu=user&mod=imgp&id='.$sql_id.'&post=submit" method="post">';
		echo '<span class="underline2">Image de profil</span><br />(JPG, JPEG, PNG ou GIF)<br />| max. 250/250 pixels 25 ko :';
		echo '<input type="hidden" name="MAX_FILE_SIZE" value="MAX_SIZE" />';
		echo '<input name="imgp" type="file" id="fichier_a_uploader" />';
	}
	else
	{
		echo '<div id="borderfleft"><a href="panel_admin.php?menu=user&mod=img_profil&id='.$sql_id.'">'.$sql_img_profil.'</a></div>';
	}
	
	echo '</div>'; //<div id="profil">';
	
	echo '<div id="nexttoprofil">';
	
	//////////////////////
	// Le mot de passe
	if(!empty($_GET['mod']) && $_GET['mod'] == 'mdp')
	{
		echo '<form action="panel_admin.php?menu=user&mod=mdp&id='.$sql_id.'&post=submit" method="post">';
		echo 'Modifier le mot de passe (<a href="panel_admin.php?menu=user&id='.$sql_id.'">Retour</a>)<br/><br/>';
		echo '<div id="qlqborder">';
		echo '<label for="newpass1">Nouveau mot de passe : </label><input type="password" name="newpass1" id="newpass1" /><br />';
		echo '<label for="newpass2">Répetez nouveau mot de passe : </label><input type="password" name="newpass2" id="newpass2" /><br />';
		echo '</div>';
	}
	else
	{
		echo 'Modifier le mot de passe (<a href="panel_admin.php?menu=user&id='.$sql_id.'&mod=mdp">Modifier</a>)';
	}
	echo '<br/>';
	//////////////////////
	// L'adresse e-mail
	if(!empty($_GET['mod']) && $_GET['mod'] == 'mail')
	{
		echo '<form action="panel_admin.php?menu=user&mod=mail&id='.$sql_id.'&post=submit" method="post">';
		echo 'Modifier l\'adresse e-mail : <span class="underline2">'.$sql_mail.'</span> (<a href="panel_admin.php?menu=user&id='.$sql_id.'">Retour</a>)<br/><br/>';
		echo '<div id="qlqborder">';
		echo '<label for="newpass1">Nouvelle adresse e-mail : </label><input type="text" name="newmail" id="newmail" /><br />';
		echo '</div>';
	}
	else
	{
		echo 'Modifier l\'adresse e-mail : <span class="underline2">'.$sql_mail.'</span> (<a href="panel_admin.php?menu=user&id='.$sql_id.'&mod=mail">Modifier</a>)';
	}
	echo '</div>';//<div id="nexttoprofil">';
	echo '</div>'; //<div class="flex_prfl">';
	echo '</div>'; //<div id="menumarg">'; La bordure autour noir
	
	if(!empty($_GET['mod'])){
		echo '<div id="annonce"><span class="underline">Attention </span><span class="vert">!</span> Vous allez modifier le Profil de '.$sql_username.'</div>';
	}
	// ici le bouton pour valider le formulaire
	if(!empty($_GET['mod']))
	{
		echo '<div id="center">';
		echo '<input type="submit" value="Modifier" />';
		echo '</form>';
		echo '</div>';
	}
	
?>