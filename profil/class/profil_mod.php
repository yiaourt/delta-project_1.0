<?php 

class profil_mod{
	
	public function usrMod(){
		if(!empty($_GET['mod']) && $_GET['mod'] == 'usr')
		{
			echo $_SESSION['pseudo'].' (<a href="profil.php">Retour</a>)';
			echo '<form action="profil_submit.php" method="post">';
			echo '<div id="block"><input type="text" name="usr" /></div>';
		}
		else
		{
			echo '<a href="profil.php?mod=usr" class="titleuser">'.$_SESSION['pseudo'].'</a>';
		}
	}
	
	public function imgMod(){
		if(!empty($_GET['mod']) && $_GET['mod'] == 'img_profil')
		{
			echo '<div>'.$_SESSION['icone_img_profil'].'</div>';
			
			echo "(<a href='profil.php'>Retour</a>) ";
			
			echo '<form enctype="multipart/form-data" action="profil_submit.php?mod=img_profil" method="post">';
			echo '<span class="underline2">Image de profil</span><br />(JPG, JPEG, PNG ou GIF)<br />| maximum 250 x 250 pixels, 2 Mo :';
			echo '<input type="hidden" name="MAX_FILE_SIZE" value="MAX_SIZE" />';
			echo '<input name="imgp" type="file" id="fichier_a_uploader" />';
		}
		else
		{
			echo '<div><a href="profil.php?mod=img_profil">'.$_SESSION['img_profil'].'</a></div>';
		}
	}
	
	public function mdpMod(){
		if(!empty($_GET['mod']) && $_GET['mod'] == 'mdp')
		{
			echo '<form action="profil_submit.php" method="post">';
			echo 'Modifier le mot de passe (<a href="profil.php">Retour</a>)<br/><br/>';
			echo '<div id="qlqborder">';
			echo '<label for="newpass1">Nouveau mot de passe : </label><input type="password" name="newpass1" id="newpass1" /><br />';
			echo '<label for="newpass2">Répetez nouveau mot de passe : </label><input type="password" name="newpass2" id="newpass2" /><br />';
			echo '</div>';
		}
		else
		{
			echo 'Modifier le mot de passe (<a href="profil.php?mod=mdp">Modifier</a>)';
		}
	}
	
	public function mailMod(){
		if(!empty($_GET['mod']) && $_GET['mod'] == 'mail')
		{
			echo '<form action="profil_submit.php" method="post">';
			echo 'Modifier l\'adresse e-mail : <span class="underline2">'.$_SESSION['mail'].'</span> (<a href="profil.php">Retour</a>)<br/><br/>';
			echo '<div id="qlqborder">';
			echo '<label for="newpass1">Nouvelle adresse e-mail : </label><input type="text" name="newmail" id="newmail" /><br />';
			echo '</div>';
		}
		else
		{
			echo 'Modifier l\'adresse e-mail : <span class="underline2">'.$_SESSION['mail'].'</span> (<a href="profil.php?mod=mail">Modifier</a>)';
		}
	}
}
?>