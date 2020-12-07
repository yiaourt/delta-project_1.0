<?php 
class upload{
	public function new($temp_file1, $temp_file2, $temp_file3, $temp_file4, $temp_file5){
		if(!empty($temp_file1)){ // On vérifie que l'image principale existe
			
			// On vérifie chaque érreur du fichier
			if($_FILES['imgArticle1']['error'] > 0) $msg = 'Erreur lors du transfert';

			$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' ); // On vérifie son extension
			$extension_upload = strtolower(  substr(  strrchr($_FILES['imgArticle1']['name'], '.')  ,1)  );
			
			if(in_array($extension_upload, $extensions_valides)){

				$racine_nom = $_SERVER['DOCUMENT_ROOT'].'/img/shop/'.$_FILES['imgArticle1']['name'];
				
				$nom = $_FILES['imgArticle1']['name'];

				if (is_uploaded_file($temp_file1)) {
					
					if(move_uploaded_file($temp_file1, $racine_nom)){ // On place l'image dans /img/shop/nom_de_l'image
						 
						if(empty($msg)){
							
							// On récupere l'image de l'article
							$imgArticle1 = "img/shop/".$nom;

						}else{
							echo '<div id="article"><div id="annonce">';
							echo '<div id="center"><p>'.$msg.'</p>Vous allez être rediriger...<br/>
							<progress id=\'prog\' max=100>';
							
							echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 4200);</script>';
							echo '</div></div></div>'; 
							exit;
						}
					}else{
						echo '<div id="article"><div id="annonce">';
						echo '<div id="center"><p>Erreur : Problème de fichier, veuillez contacter un administateur</p>';

						echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 4200);</script>';
						echo '</div></div></div>'; 
						exit;
					}
				}
			}
		}elseif(empty($temp_file1)){
			$imgArticle1 = 'img/shop/basic.png';
		}

		if(!empty($temp_file2)){ // Si une 2eme image est envoyer.
			
			// On vérifie chaque érreur du fichier
			if($_FILES['imgArticle2']['error'] > 0) $msg = 'Erreur lors du transfert';

			$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' ); // On vérifie son extension
			$extension_upload = strtolower(  substr(  strrchr($_FILES['imgArticle2']['name'], '.')  ,1)  );
			
			if(in_array($extension_upload, $extensions_valides)){

				$racine_nom = $_SERVER['DOCUMENT_ROOT'].'/img/shop/'.$_FILES['imgArticle2']['name'];
				
				$nom = $_FILES['imgArticle2']['name'];

				if (is_uploaded_file($temp_file2)) {
					
					if(move_uploaded_file($temp_file2, $racine_nom)){ // On place l'image dans /img/shop/nom_de_l'image
						 
						if(empty($msg)){
							
							// On récupere l'image de l'article
							$imgArticle2 = "img/shop/".$nom;

						}else{
							echo '<div id="article"><div id="annonce">';
							echo '<div id="center"><p>'.$msg.'</p>Vous allez être rediriger...<br/>
							<progress id=\'prog\' max=100>';
							
							echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 4200);</script>';
							echo '</div></div></div>'; 
							exit;
						}
					}else{
						echo '<div id="article"><div id="annonce">';
						echo '<div id="center"><p>Erreur : Problème de fichier, veuillez contacter un administateur PHP</p>';

						echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 4200);</script>';
						echo '</div></div></div>'; 
						exit;
					}
				}
			}
		}elseif(empty($temp_file2)){
			$imgArticle2 = '';
		}

		if(!empty($temp_file3)){ // Si une 2eme image est envoyer.
			
			// On vérifie chaque érreur du fichier
			if($_FILES['imgArticle3']['error'] > 0) $msg = 'Erreur lors du transfert';

			$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' ); // On vérifie son extension
			$extension_upload = strtolower(  substr(  strrchr($_FILES['imgArticle3']['name'], '.')  ,1)  );
			
			if(in_array($extension_upload, $extensions_valides)){

				$racine_nom = $_SERVER['DOCUMENT_ROOT'].'/img/shop/'.$_FILES['imgArticle3']['name'];
				
				$nom = $_FILES['imgArticle3']['name'];

				if (is_uploaded_file($temp_file3)) {
					
					if(move_uploaded_file($temp_file3, $racine_nom)){ // On place l'image dans /img/shop/nom_de_l'image
						 
						if(empty($msg)){
							
							// On récupere l'image de l'article
							$imgArticle3 = "img/shop/".$nom;

						}else{
							echo '<div id="article"><div id="annonce">';
							echo '<div id="center"><p>'.$msg.'</p>Vous allez être rediriger...<br/>
							<progress id=\'prog\' max=100>';
							
							echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 4200);</script>';
							echo '</div></div></div>'; 
							exit;
						}
					}else{
						echo '<div id="article"><div id="annonce">';
						echo '<div id="center"><p>Erreur : Problème de fichier, veuillez contacter un administateur PHP</p>';

						echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 4200);</script>';
						echo '</div></div></div>'; 
						exit;
					}
				}
			}
		}elseif(empty($temp_file3)){
			$imgArticle3 = '';
		}

		if(!empty($temp_file4)){ // Si une 2eme image est envoyer.
			
			// On vérifie chaque érreur du fichier
			if($_FILES['imgArticle4']['error'] > 0) $msg = 'Erreur lors du transfert';

			$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' ); // On vérifie son extension
			$extension_upload = strtolower(  substr(  strrchr($_FILES['imgArticle4']['name'], '.')  ,1)  );
			
			if(in_array($extension_upload, $extensions_valides)){

				$racine_nom = $_SERVER['DOCUMENT_ROOT'].'/img/shop/'.$_FILES['imgArticle4']['name'];
				
				$nom = $_FILES['imgArticle4']['name'];

				if (is_uploaded_file($temp_file4)) {
					
					if(move_uploaded_file($temp_file4, $racine_nom)){ // On place l'image dans /img/shop/nom_de_l'image
						 
						if(empty($msg)){
							
							// On récupere l'image de l'article
							$imgArticle4 = "img/shop/".$nom;

						}else{
							echo '<div id="article"><div id="annonce">';
							echo '<div id="center"><p>'.$msg.'</p>Vous allez être rediriger...<br/>
							<progress id=\'prog\' max=100>';
							
							echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 4200);</script>';
							echo '</div></div></div>'; 
							exit;
						}
					}else{
						echo '<div id="article"><div id="annonce">';
						echo '<div id="center"><p>Erreur : Problème de fichier, veuillez contacter un administateur PHP</p>';

						echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 4200);</script>';
						echo '</div></div></div>'; 
						exit;
					}
				}
			}
		}elseif(empty($temp_file4)){
			$imgArticle4 = '';
		}

		if(!empty($temp_file5)){ // Si une 2eme image est envoyer.
			
			// On vérifie chaque érreur du fichier
			if($_FILES['imgArticle5']['error'] > 0) $msg = 'Erreur lors du transfert';

			$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' ); // On vérifie son extension
			$extension_upload = strtolower(  substr(  strrchr($_FILES['imgArticle5']['name'], '.')  ,1)  );
			
			if(in_array($extension_upload, $extensions_valides)){

				$racine_nom = $_SERVER['DOCUMENT_ROOT'].'/img/shop/'.$_FILES['imgArticle5']['name'];
				
				$nom = $_FILES['imgArticle5']['name'];

				if (is_uploaded_file($temp_file5)) {
					
					if(move_uploaded_file($temp_file5, $racine_nom)){ // On place l'image dans /img/shop/nom_de_l'image
						 
						if(empty($msg)){
							
							// On récupere l'image de l'article
							$imgArticle5 = "img/shop/".$nom;

						}else{
							echo '<div id="article"><div id="annonce">';
							echo '<div id="center"><p>'.$msg.'</p>Vous allez être rediriger...<br/>
							<progress id=\'prog\' max=100>';
							
							echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 4200);</script>';
							echo '</div></div></div>'; 
							exit;
						}
					}else{
						echo '<div id="article"><div id="annonce">';
						echo '<div id="center"><p>Erreur : Problème de fichier, veuillez contacter un administateur PHP</p>';

						echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 4200);</script>';
						echo '</div></div></div>'; 
						exit;
					}
				}
			}
		}elseif(empty($temp_file5)){
			$imgArticle5 = '';
		}

		return array($imgArticle1, $imgArticle2, $imgArticle3, $imgArticle4, $imgArticle5);
	}

	// On fait un envois de fichier différent pour la modification d'un article car il faut prendre en compte que l'article existe déjà dans la base de donnée on récupere aussi l'id de l'article
	public function update($id, $temp_file1, $temp_file2, $temp_file3, $temp_file4, $temp_file5){

		$bdd = connectSql(); // On aura besoin de la connexion sql

		if(!empty($temp_file1)){ // On vérifie que l'image principale existe
			
			// On vérifie chaque érreur du fichier
			if($_FILES['imgArticle1']['error'] > 0) $msg = 'Erreur lors du transfert';

			$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' ); // On vérifie son extension
			$extension_upload = strtolower(  substr(  strrchr($_FILES['imgArticle1']['name'], '.')  ,1)  );
			
			if(in_array($extension_upload, $extensions_valides)){

				$racine_nom = $_SERVER['DOCUMENT_ROOT'].'/img/shop/'.$_FILES['imgArticle1']['name'];
				
				$nom = $_FILES['imgArticle1']['name'];

				if (is_uploaded_file($temp_file1)) {
					
					if(move_uploaded_file($temp_file1, $racine_nom)){ // On place l'image dans /img/shop/nom_de_l'image
						 
						if(empty($msg)){
							
							// On récupere l'image de l'article
							$imgArticle1 = "img/shop/".$nom;

							// On envoit ensuite sur la base de données table shop
							$reqimg1 = $bdd->prepare('UPDATE shop SET image1 = :imgArticle1 WHERE id = :id');

							$reqimg1->execute(array(
																'imgArticle1' => $imgArticle1,

																'id' => $id
							));

							$reqimg1->closeCursor();

						}else{
							echo '<div id="article"><div id="annonce">';
							echo '<div id="center"><p>'.$msg.'</p>Vous allez être rediriger...<br/>
							<progress id=\'prog\' max=100>';
							
							echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 4200);</script>';
							echo '</div></div></div>'; 
							exit;
						}
					}else{
						echo '<div id="article"><div id="annonce">';
						echo '<div id="center"><p>Erreur : Problème de fichier, veuillez contacter un administateur</p>';

						echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 4200);</script>';
						echo '</div></div></div>'; 
						exit;
					}
				}
			}
		}

		if(!empty($temp_file2)){ // Si une 2eme image est envoyer.
			
			// On vérifie chaque érreur du fichier
			if($_FILES['imgArticle2']['error'] > 0) $msg = 'Erreur lors du transfert';

			$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' ); // On vérifie son extension
			$extension_upload = strtolower(  substr(  strrchr($_FILES['imgArticle2']['name'], '.')  ,1)  );
			
			if(in_array($extension_upload, $extensions_valides)){

				$racine_nom = $_SERVER['DOCUMENT_ROOT'].'/img/shop/'.$_FILES['imgArticle2']['name'];
				
				$nom = $_FILES['imgArticle2']['name'];

				if (is_uploaded_file($temp_file2)) {
					
					if(move_uploaded_file($temp_file2, $racine_nom)){ // On place l'image dans /img/shop/nom_de_l'image
						 
						if(empty($msg)){
							
							// On récupere l'image de l'article
							$imgArticle2 = "img/shop/".$nom;

							// On envoit ensuite sur la base de données table shop
							$reqimg2 = $bdd->prepare('UPDATE shop SET image2 = :imgArticle2 WHERE id = :id');

							$reqimg2->execute(array(
																'imgArticle2' => $imgArticle2,

																'id' => $id
							));

							$reqimg2->closeCursor();

						}else{
							echo '<div id="article"><div id="annonce">';
							echo '<div id="center"><p>'.$msg.'</p>Vous allez être rediriger...<br/>
							<progress id=\'prog\' max=100>';
							
							echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 4200);</script>';
							echo '</div></div></div>'; 
							exit;
						}
					}else{
						echo '<div id="article"><div id="annonce">';
						echo '<div id="center"><p>Erreur : Problème de fichier, veuillez contacter un administateur PHP</p>';

						echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 4200);</script>';
						echo '</div></div></div>'; 
						exit;
					}
				}
			}
		}

		if(!empty($temp_file3)){ // Si une 2eme image est envoyer.
			
			// On vérifie chaque érreur du fichier
			if($_FILES['imgArticle3']['error'] > 0) $msg = 'Erreur lors du transfert';

			$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' ); // On vérifie son extension
			$extension_upload = strtolower(  substr(  strrchr($_FILES['imgArticle3']['name'], '.')  ,1)  );
			
			if(in_array($extension_upload, $extensions_valides)){

				$racine_nom = $_SERVER['DOCUMENT_ROOT'].'/img/shop/'.$_FILES['imgArticle3']['name'];
				
				$nom = $_FILES['imgArticle3']['name'];

				if (is_uploaded_file($temp_file3)) {
					
					if(move_uploaded_file($temp_file3, $racine_nom)){ // On place l'image dans /img/shop/nom_de_l'image
						 
						if(empty($msg)){
							
							// On récupere l'image de l'article
							$imgArticle3 = "img/shop/".$nom;

							// On envoit ensuite sur la base de données table shop
							$reqimg3 = $bdd->prepare('UPDATE shop SET image3 = :imgArticle3 WHERE id = :id');

							$reqimg3->execute(array(
																'imgArticle3' => $imgArticle3,

																'id' => $id
							));

							$reqimg3->closeCursor();

						}else{
							echo '<div id="article"><div id="annonce">';
							echo '<div id="center"><p>'.$msg.'</p>Vous allez être rediriger...<br/>
							<progress id=\'prog\' max=100>';
							
							echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 4200);</script>';
							echo '</div></div></div>'; 
							exit;
						}
					}else{
						echo '<div id="article"><div id="annonce">';
						echo '<div id="center"><p>Erreur : Problème de fichier, veuillez contacter un administateur PHP</p>';

						echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 4200);</script>';
						echo '</div></div></div>'; 
						exit;
					}
				}
			}
		}

		if(!empty($temp_file4)){ // Si une 2eme image est envoyer.
			
			// On vérifie chaque érreur du fichier
			if($_FILES['imgArticle4']['error'] > 0) $msg = 'Erreur lors du transfert';

			$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' ); // On vérifie son extension
			$extension_upload = strtolower(  substr(  strrchr($_FILES['imgArticle4']['name'], '.')  ,1)  );
			
			if(in_array($extension_upload, $extensions_valides)){

				$racine_nom = $_SERVER['DOCUMENT_ROOT'].'/img/shop/'.$_FILES['imgArticle4']['name'];
				
				$nom = $_FILES['imgArticle4']['name'];

				if (is_uploaded_file($temp_file4)) {
					
					if(move_uploaded_file($temp_file4, $racine_nom)){ // On place l'image dans /img/shop/nom_de_l'image
						 
						if(empty($msg)){
							
							// On récupere l'image de l'article
							$imgArticle4 = "img/shop/".$nom;

							// On envoit ensuite sur la base de données table shop
							$reqimg4 = $bdd->prepare('UPDATE shop SET image4 = :imgArticle4 WHERE id = :id');

							$reqimg4->execute(array(
																'imgArticle4' => $imgArticle4,

																'id' => $id
							));

							$reqimg4->closeCursor();

						}else{
							echo '<div id="article"><div id="annonce">';
							echo '<div id="center"><p>'.$msg.'</p>Vous allez être rediriger...<br/>
							<progress id=\'prog\' max=100>';
							
							echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 4200);</script>';
							echo '</div></div></div>'; 
							exit;
						}
					}else{
						echo '<div id="article"><div id="annonce">';
						echo '<div id="center"><p>Erreur : Problème de fichier, veuillez contacter un administateur PHP</p>';

						echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 4200);</script>';
						echo '</div></div></div>'; 
						exit;
					}
				}
			}
		}

		if(!empty($temp_file5)){ // Si une 2eme image est envoyer.
			
			// On vérifie chaque érreur du fichier
			if($_FILES['imgArticle5']['error'] > 0) $msg = 'Erreur lors du transfert';

			$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' ); // On vérifie son extension
			$extension_upload = strtolower(  substr(  strrchr($_FILES['imgArticle5']['name'], '.')  ,1)  );
			
			if(in_array($extension_upload, $extensions_valides)){

				$racine_nom = $_SERVER['DOCUMENT_ROOT'].'/img/shop/'.$_FILES['imgArticle5']['name'];
				
				$nom = $_FILES['imgArticle5']['name'];

				if (is_uploaded_file($temp_file5)) {
					
					if(move_uploaded_file($temp_file5, $racine_nom)){ // On place l'image dans /img/shop/nom_de_l'image
						 
						if(empty($msg)){
							
							// On récupere l'image de l'article
							$imgArticle5 = "img/shop/".$nom;

							// On envoit ensuite sur la base de données table shop
							$reqimg5 = $bdd->prepare('UPDATE shop SET image5 = :imgArticle5 WHERE id = :id');

							$reqimg1->execute(array(
																'imgArticle5' => $imgArticle5,

																'id' => $id
							));

							$reqimg5->closeCursor();

						}else{
							echo '<div id="article"><div id="annonce">';
							echo '<div id="center"><p>'.$msg.'</p>Vous allez être rediriger...<br/>
							<progress id=\'prog\' max=100>';
							
							echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 4200);</script>';
							echo '</div></div></div>'; 
							exit;
						}
					}else{
						echo '<div id="article"><div id="annonce">';
						echo '<div id="center"><p>Erreur : Problème de fichier, veuillez contacter un administateur PHP</p>';

						echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 4200);</script>';
						echo '</div></div></div>'; 
						exit;
					}
				}
			}
		}
	}
}
?>