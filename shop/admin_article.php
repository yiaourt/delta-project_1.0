<?php 

class admin_article{

	public function newArticle(){ // La page qui permet de créer un nouvelle article dans le shop

		$bdd = connectSql();

		echo '<article>';
		echo '<a href="shop.php"><- Retour au Shop 3D</a>';

		echo '<h2>Ajouter un nouvelle article : </h2><hr/>';

		echo '<form enctype="multipart/form-data" action="shop.php?article=send_new" method="post">';

		if(!empty($_GET['error']) && $_GET['error'] == '1'){ echo '<div id="annonce">Erreur dans le titre...</div>';}
		echo '<u>Titre :</u> <textarea name="title" class="low"></textarea> <br/> <br/>';

		if(!empty($_GET['error']) && $_GET['error'] == '2'){ echo '<div id="annonce">Erreur dans la description...</div>';}
		echo '<u>Description :</u><br/> <textarea name="description" id="editor"></textarea> <br/> <br/>';

		// On flex row le prix, les images et le choix des catégories
		echo '<div id="flex_new_article">';

		echo '<div id="flex_prix_image">';
		if(!empty($_GET['error']) && $_GET['error'] == '3'){ echo '<div id="annonce">Erreur dans le prix...</div>';}
		echo '<u>Prix :</u> <textarea name="prix" class="verylow"></textarea> <br/> 
				(<u>example :</u> 20 pour 20euros) <br/> <br/>';


		if(!empty($_GET['error']) && $_GET['error'] == '4' && !empty($_GET['msg'])){ echo '<div id="annonce">'.$_GET['msg'].'</div>';}
		echo '<u>Ajouter une image principal :</u><br/>';

		echo '<input type="hidden" name="MAX_FILE_SIZE" value="MAX_SIZE" />';
		echo '<input name="imgArticle1" type="file" id="fichier_a_uploader" />';

		echo '<br/> <br/> <u>Ajouter une 2eme image :</u> (facultatif)<br/>';

		echo '<input type="hidden" name="MAX_FILE_SIZE" value="MAX_SIZE" />';
		echo '<input name="imgArticle2" type="file" id="fichier_a_uploader" />';

		echo '<br/> <br/> <u>Ajouter une 3eme image :</u> (facultatif)<br/>';

		echo '<input type="hidden" name="MAX_FILE_SIZE" value="MAX_SIZE" />';
		echo '<input name="imgArticle3" type="file" id="fichier_a_uploader" />';

		echo '<br/> <br/> <u>Ajouter une 4eme image :</u> (facultatif)<br/>';

		echo '<input type="hidden" name="MAX_FILE_SIZE" value="MAX_SIZE" />';
		echo '<input name="imgArticle4" type="file" id="fichier_a_uploader" />';

		echo '<br/> <br/> <u>Ajouter une 5eme image :</u> (facultatif)<br/>';

		echo '<input type="hidden" name="MAX_FILE_SIZE" value="MAX_SIZE" />';
		echo '<input name="imgArticle5" type="file" id="fichier_a_uploader" />';

		echo '</div>';

		echo '<div id="flex_category">';

		echo '<fieldset id="category_field"><legend>Categorie :</legend>';
		echo '<textarea name="category" id="category_form"></textarea>';
		echo '<br/>';

		/////////////////////////////////////////////////////////
		echo '<u>Catégorie(s) à retirer :</u><br/>';

		echo '<div id="admin_undrop_category">';

		$reqshop_cat = $bdd->query('SELECT * FROM shop_category');

		while($sqlshop_cat = $reqshop_cat->fetch()){
			echo '<span id="admin_shop_category" ondrag="initialize_varID('.$sqlshop_cat['id'].')">'.$sqlshop_cat['name'].'</span>';
		}
		$reqshop_cat->closeCursor();

		echo '</div>';

		//////////////////////////////////////////////////////
		echo '<br/><u>Catégorie(s) à ajouter :</u><br/><div id="admin_drop_category"></div>';

		echo '</div>';

		echo '</div>';

		echo '</fieldset>';

		echo '<br/>';

		echo '<div id="center"><button id="savebutton">Enregistrer</button></div>';

		echo '<script src="inc/create_ckeditor.js"></script>';
		echo '<script src="inc/js/shop_admin.js"></script>';

		echo '</form>';

		echo '</article>';
	}

	public function sendArticle(){ // La page qui permet d'envoyer un nouvelle article dans le shop
		
		$bdd = connectSql(); // On se connecte à sql car on va envoyer l'article sur la base de donnée

		// On vérifie les érreurs
		if(empty($_POST['title'])){
			echo '<script language="Javascript">
				setTimeout(function(){document.location.replace("shop.php?article=new&error=1")}, 1200);
			
			</script>';
			exit;
		}

		if(empty($_POST['description'])){
			echo '<script language="Javascript">
				setTimeout(function(){document.location.replace("shop.php?article=new&error=2")}, 1200);
			
			</script>';
			exit;
		}

		if(empty($_POST['prix'])){
			echo '<script language="Javascript">
				setTimeout(function(){document.location.replace("shop.php?article=new&error=3")}, 1200);
			
			</script>';
			exit;
		} //---------------------------


		// On récupere les variables post
		$titre = $_POST['title'];
		$description = $_POST['description'];
		$prix = $_POST['prix'];

		// On récupere les catégories ajouter
		$category = $_POST['category'];

		// On récupere les images
		$temp_file1 = $_FILES['imgArticle1']['tmp_name'];
		$temp_file2 = $_FILES['imgArticle2']['tmp_name'];
		$temp_file3 = $_FILES['imgArticle3']['tmp_name'];
		$temp_file4 = $_FILES['imgArticle4']['tmp_name'];
		$temp_file5 = $_FILES['imgArticle5']['tmp_name'];

		require('shop/func/upload_img.php'); // On récupere le fichier qui se charge d'upload les images.
		// Le fichier ci dessus renvois les variables $imgArticle1 2 3 4 et 5 vide ou non
		$upload = new upload;

		list($imgArticle1, $imgArticle2, $imgArticle3, $imgArticle4, $imgArticle5) = $upload->new($temp_file1, $temp_file2, $temp_file3, $temp_file4, $temp_file5);

		// On envoit ensuite sur la base de données table shop
		$reqshop = $bdd->prepare('INSERT INTO shop(titre, description, prix, category, image1, image2, image3, image4, image5) VALUES(:titre, :description, :prix, :category, :image1, :image2, :image3, :image4, :image5)');

		$reqshop->execute(array(
											'titre' => $titre,
											'description' => $description,
											'prix' => $prix,
											'category' => $category,
											'image1' => $imgArticle1,
											'image2' => $imgArticle2,
											'image3' => $imgArticle3,
											'image4' => $imgArticle4,
											'image5' => $imgArticle5
		));

		$reqshop->closeCursor();

		echo '<div id="borderbgnomarg"><div id="bordercenter">';
		echo '<div id="center"><p>Article ajouter avec succés.<br/>Vous allez être rediriger...';
		echo '</div></div></div>';
		echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 2900);</script>';
		exit;

	}

	public function newCategory(){ // La page qui permet de créer une nouvelle  catégorie d'article dans le shop
			echo '<article>';
			echo '<a href="shop.php"><- Retour au Shop 3D</a>';

			echo '<h2>Ajouter une nouvelle catégorie d\'article : </h2><hr/>';

			echo '<form action="shop.php?article=send_category" method="post">';

			if(!empty($_GET['error']) && $_GET['error'] == '1'){ echo '<div id="annonce">Erreur : Il faut rentrer un nom de catégorie...</div>';}
			echo 'Nom de la catégorie : <textarea name="name" class="low"></textarea>';

			echo '<br/><br/><button id="savebutton">Ajouter</button>';

			echo '</form>';

			echo '</article>';
	}

	public function sendCategory(){ // La page qui permet d'envoyer la nouvelle catégorie dans le shop
		$bdd = connectSql(); // On se connecte à sql car on va envoyer la catégorie sur la base de donnée à la table shop_category

		// On vérifie les érreurs
		if(empty($_POST['name'])){
			echo '<script language="Javascript">
				setTimeout(function(){document.location.replace("shop.php?article=new_category&error=1")}, 1200);
			
			</script>';
			exit;
		}

		// On envois la nouvelle catégorie sur la base de données
		// On envoit ensuite sur la base de données table shop
		$reqshop_cat = $bdd->prepare('INSERT INTO shop_category(name) VALUES(:name)');

		$reqshop_cat->execute(array(
											'name' => $_POST['name']
		));

		$reqshop_cat->closeCursor();

		echo '<div id="borderbgnomarg"><div id="bordercenter">';
		echo '<div id="center"><p>Catégorie ajouter avec succés.<br/>Vous allez être rediriger...';
		echo '</div></div></div>';
		echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 2900);</script>';
		exit;
	}

	public function modArticle(){

		$bdd = connectSql(); // On se connecte à sql

		$reqshop_mod = $bdd->prepare('SELECT * FROM shop WHERE id=:id');

		$sqlshop_mod = $reqshop_mod->execute(array(
			'id' => $_GET['id']
		));

		while($sqlshop_mod = $reqshop_mod->fetch()){ // Ici on récupere l'articles du shop selon l'id de l'article demander puis on l'affiche.
			
			// On fait un lien de retour à la galerie
			echo '<p><a href="shop.php"><- Retour à la galerie</a></p>';
			
			
			echo '<hr/>'; // Ligne horyzontal 

			// On ouvre le formulaire
			echo '<form enctype="multipart/form-data" action="shop.php?article=update&id='.$sqlshop_mod['id'].'" method="post">';

			echo '<div id="article_flex">'; // Conteneur flex

			// On créer ici l'image cliquable dynamiquement par javascript 
			echo '<a href="#" onclick="chargeImage('.$sqlshop_mod['id'].')">';

			// L'image de l'article
			echo '<div id="conteneur_img_article">';
			echo '<img id="img_article" src="'.$sqlshop_mod['image1'].'" alt="'.$sqlshop_mod['titre'].'" />';
			echo '</div>';

			// les icones des images de l'article
			echo '<div id="conteneur_icone_img_article">';
			if(!empty($sqlshop_mod['image1'])){
				echo '<img id="icone_img_article" src="'.$sqlshop_mod['image1'].'" alt="'.$sqlshop_mod['titre'].'" />';
			}

			if(!empty($sqlshop_mod['image2'])){
				echo '<img id="icone_img_article" src="'.$sqlshop_mod['image2'].'" alt="'.$sqlshop_mod['titre'].'" />';
			}
			if(!empty($sqlshop_mod['image3'])){
				echo '<img id="icone_img_article" src="'.$sqlshop_mod['image3'].'" alt="'.$sqlshop_mod['titre'].'" />';
			}
			if(!empty($sqlshop_mod['image4'])){
				echo '<img id="icone_img_article" src="'.$sqlshop_mod['image4'].'" alt="'.$sqlshop_mod['titre'].'" />';
			}
			if(!empty($sqlshop_mod['image5'])){
				echo '<img id="icone_img_article" src="'.$sqlshop_mod['image5'].'" alt="'.$sqlshop_mod['titre'].'" />';
			}
			echo '</div>';

			echo '</a>';


			if(!empty($_GET['error']) && $_GET['error'] == '4' && !empty($_GET['msg'])){ echo '<div id="annonce">'.$_GET['msg'].'</div>';}
			// On fait un bloc de la modification de l'image qui sera cacher en javascript.
			echo '<div id="mod_img_article">';
			echo '<u>ajouter/modifier l\'image principal :</u><br/>';

			echo '<input type="hidden" name="MAX_FILE_SIZE" value="MAX_SIZE" />';
			echo '<input name="imgArticle1" type="file" id="fichier_a_uploader" />';

			echo '<br/> <br/> <u>ajouter/modifier la 2eme image :</u> (facultatif)<br/>';

			echo '<input type="hidden" name="MAX_FILE_SIZE" value="MAX_SIZE" />';
			echo '<input name="imgArticle2" type="file" id="fichier_a_uploader" />';

			echo '<br/> <br/> <u>ajouter/modifier la 3eme image :</u> (facultatif)<br/>';

			echo '<input type="hidden" name="MAX_FILE_SIZE" value="MAX_SIZE" />';
			echo '<input name="imgArticle3" type="file" id="fichier_a_uploader" />';

			echo '<br/> <br/> <u>ajouter/modifier la 4eme image :</u> (facultatif)<br/>';

			echo '<input type="hidden" name="MAX_FILE_SIZE" value="MAX_SIZE" />';
			echo '<input name="imgArticle4" type="file" id="fichier_a_uploader" />';

			echo '<br/> <br/> <u>ajouter/modifier la 5eme image :</u> (facultatif)<br/>';

			echo '<input type="hidden" name="MAX_FILE_SIZE" value="MAX_SIZE" />';
			echo '<input name="imgArticle5" type="file" id="fichier_a_uploader" />';

			echo '<br/> <a href="#" onclick="dechargeImage()"><- Annuler</a>
					</div>';

			if(!empty($_GET['error']) && $_GET['error'] == '1'){ echo '<div id="annonce">Erreur dans le titre...</div>';}
			// On ajoute ensuite le titre et la description dans des formulaire textarea.
			echo '<div id="article_text"><textarea name="title" class="low">'.$sqlshop_mod['titre'].'</textarea> <hr/>';

			if(!empty($_GET['error']) && $_GET['error'] == '2'){ echo '<div id="annonce">Erreur dans la description...</div>';}
			echo '<textarea name="description" id="editor">'.$sqlshop_mod['description'].'</textarea> <hr/>';

			if(!empty($_GET['error']) && $_GET['error'] == '3'){ echo '<div id="annonce">Erreur dans le prix...</div>';}
			echo '<br/> <br/> <br/> <h2 id="article_prix">Prix : <textarea name="prix" class="verylow">'.$sqlshop_mod['prix'].'</textarea> €</h2>

					<br/><button id="savebutton">Enregistrer</button>
				</div>';

			echo '<hr/>';

			echo '</div>';

			echo '<script src="inc/create_ckeditor.js"></script>'; // On charge le script de l'éditeur de texte ckeditor4
			echo '<script src="shop/js/shop_admin.js"></script>'; // On charge le script du shop admin

			echo '</form>';

		}
		$reqshop_mod->closeCursor();

	}

	public function updateArticle($id){ // La page qui permet d'envoyer un nouvelle article dans le shop
		
		$bdd = connectSql(); // On se connecte à sql car on va envoyer l'article sur la base de donnée

		// On vérifie les érreurs
		if(empty($_POST['title'])){
			echo '<script language="Javascript">
				setTimeout(function(){document.location.replace("shop.php?article=mod&id='.$id.'&error=1")}, 1200);
			
			</script>';
			exit;
		}

		if(empty($_POST['description'])){
			echo '<script language="Javascript">
				setTimeout(function(){document.location.replace("shop.php?article=mod&id='.$id.'&error=2")}, 1200);
			
			</script>';
			exit;
		}

		if(empty($_POST['prix'])){
			echo '<script language="Javascript">
				setTimeout(function(){document.location.replace("shop.php?article=mod&id='.$id.'&error=3")}, 1200);
			
			</script>';
			exit;
		} //---------------------------


		// On récupere les variables post
		$titre = $_POST['title'];
		$description = $_POST['description'];
		$prix = $_POST['prix'];

		$temp_file1 = $_FILES['imgArticle1']['tmp_name'];
		$temp_file2 = $_FILES['imgArticle2']['tmp_name'];
		$temp_file3 = $_FILES['imgArticle3']['tmp_name'];
		$temp_file4 = $_FILES['imgArticle4']['tmp_name'];
		$temp_file5 = $_FILES['imgArticle5']['tmp_name'];

		require('shop/func/upload_img.php'); // On récupere le fichier qui se charge d'upload les images.
		// Le fichier ci dessus renvois les variables $imgArticle1 2 3 4 et 5 vide ou non et pour le cas de l'update la fonction envois directement sur la base de données
		$upload = new upload;

		$upload->update($id, $temp_file1, $temp_file2, $temp_file3, $temp_file4, $temp_file5);

		// On envoit ensuite sur la base de données table shop
		$reqUPshop = $bdd->prepare('UPDATE shop SET titre = :titre, description = :description, prix = :prix WHERE id = :id');

		$reqUPshop->execute(array(
											'titre' => $titre,
											'description' => $description,
											'prix' => $prix,

											'id' => $id
		));

		$reqUPshop->closeCursor();

		echo '<div id="borderbgnomarg"><div id="bordercenter">';
		echo '<div id="center"><p>Article modifier avec succés.<br/>Vous allez être rediriger...';
		echo '</div></div></div>';
		echo '<script language="Javascript">setTimeout(function(){document.location.replace("shop.php")}, 2900);</script>';
		exit;

	}
}

?>