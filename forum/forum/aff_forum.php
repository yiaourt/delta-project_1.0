<?php class aff_forum{

	public function afficheForum(){
		$bdd = connectSql();
		
		echo '<div id="whereisuser"><span id="WIUlink"><a href="forum.php">Forum</a></span> &#10148; </div>'; // j'affiche ou ce situe l'utilisateur dans le forum

		// On fait un lien qui va ouvrir toutes les catégories en meme temps.
		echo '<a id="forum_mini_tableau" href="javascript:void(0)">&#8598; Fermer toutes les catégories &#8599;</a>';

		// On fait la requête, puis on boucle!
		$reqforum = $bdd->query('SELECT * FROM forum ORDER BY ordre ASC');

		while($sqlforum = $reqforum->fetch()){ // j'affiche les catégories dans une boucle
			
			// On créer un lien javascript sur chaques id de catégories du forum afin de les rendre dynamique
			echo '<a class="'.$sqlforum['id'].'" id="forum_tableau" href="javascript:void(0)" onclick="showForum('.$sqlforum['id'].')">';
			echo $sqlforum['titre']; // Le titre de la catégorie
			echo '</a>';

			echo '<div class="'.$sqlforum['id'].'" id="catforum">';

			echo '<li id="aff_forum"><div id="flex1">Catégories</div>';
			echo '<div id="descrinoborder">Description</div>
				<div id="nbmsg">Total</div></li>

				<hr width=\"100%\" size=\"1\" />';

			$id_cat = $sqlforum['id'];

			$reqforum2 = $bdd->prepare('SELECT * FROM sous_forum WHERE id_cat = :id_cat');	// Je récupère les sous_catégorie
			$sqlforum2 = $reqforum2->execute(array('id_cat' => $sqlforum['id'])); // selon l'id de la catégorie
			
			while($sqlforum2 = $reqforum2->fetch()){ // Puis je l'affiche dans une boucle
				
				$id_s_cat = $sqlforum2['id'];
				
				$nbmsg = 0;
				// Ici, je fais une requête pour savoir combien de sujets au total il y a sur la bdd.
				$reqforum3 = $bdd->prepare('SELECT * FROM sous_forum_topic WHERE id_s_cat = :id_s_cat');	
				
				$sqlforum3 = $reqforum3->execute(array('id_s_cat' => $id_s_cat));
				
				while($sqlforum3 = $reqforum3->fetch()){
					$nbmsg++;
				}
				$reqforum3->closeCursor();
				
				echo '<li id="aff_forum">';
				
				// l'afficheur d'alerte de notifications ci-dessous.
				//-----------------------------------------
				//print_r($_SESSION['alertidsf_'.$sqlforum2['id']]); // une aide pour afficher la variable d'alertes pour les notifications. (équivaut à 0 ou 1)

				// Par défault si l'utilisateur n'est pas connecté.
				if(empty($_SESSION['alertidsf_'.$sqlforum2['id']]) && empty($_SESSION['alertCM_'.$sqlforum2['id']])){ 
					
					echo '<div id="notifalert"><img src="img/off_';
					if($_SESSION['style'] == 'light'){
						echo 'light';
					}elseif($_SESSION['style'] == 'carbon'){
						echo 'carbon';
					}
					echo '.png" alt="off" height="30" width="30"></img></div>';
				
				}elseif(!empty($_SESSION['alertidsf_'.$sqlforum2['id']]) OR !empty($_SESSION['alertCM_'.$sqlforum2['id']])) {
					
					if($_SESSION['alertidsf_'.$sqlforum2['id']] == 1 OR $_SESSION['alertCM_'.$sqlforum2['id']] == 1) {
						
						echo '<div id="notifalert"><img src="img/on_';
						if($_SESSION['style'] == 'light'){
							echo 'light';
						}elseif($_SESSION['style'] == 'carbon'){
							echo 'carbon';
						}
						echo '.png" alt="on" height="30" width="30"></img></div>'; 
					
					}else{
						
						echo '<div id="notifalert"><img src="img/off_';
						if($_SESSION['style'] == 'light'){
							echo 'light';
						}elseif($_SESSION['style'] == 'carbon'){
							echo 'carbon';
						}
						echo '.png" alt="off" height="30" width="30"></img></div>';
					}
				
				}else{
					
					echo '<div id="notifalertTP"><img src="img/off_';
					if($_SESSION['style'] == 'light'){
						echo 'light';
					}elseif($_SESSION['style'] == 'carbon'){
						echo 'carbon';
					}
					echo '.png" alt="off" height="20" width="20"></img></div>';
				}
				//-----------------------------------------
				
				// Ci-dessous les liens de chaques sous-catégories afficher en html 
				echo '<a href="forum.php?id='.$sqlforum2['id'].'" id="flex1" class="forumtitre">'; 
				echo $sqlforum2['titre'];
				echo '</a>';
				echo '<div id="descri">'.$sqlforum2['description'].'</div>';
				echo '<div id="nbmsg">'.$nbmsg.'</div>';
				
				echo '</li>';
			}
			
			echo '</div>'; // <div id="catforum">
		}
		
		$reqforum->closeCursor();
		$reqforum2->closeCursor();
	}
}

?>