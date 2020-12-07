<?php 

class commande{
	
	// // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 
	// La page home sde gestion de commandes, ci-dessous
	public function home(){
		$bdd = connectSql();

		echo '<article>';
		echo '<div id="menumarg">';
		
		echo '<h2><u>Gestionnaire de commandes : </u></h2>';

		$req_commande = $bdd->query('SELECT * FROM commande ORDER BY status');

		while($sql_commande = $req_commande->fetch()){

			echo '<table>';

			echo '<tr>';
			echo '<th>Informations de livraison</th>';
		    echo '<th>Article(s)</th>';
		    echo '<th>Utilisateur</th>';
		    echo '<th>Date</th>';
		    echo '<th>ID</th>';
		    echo '<th>Statuts</th>';
	   		echo '</tr>';

			// On compte le nombre d'articles
			$unserialize_panier_commande = unserialize($sql_commande['articles']);

			$count = 0;

			foreach($unserialize_panier_commande as $article){
				$count = $count+1;
			}

			// On vérifie ensuite le status
			if($sql_commande['status'] == "COMPLETED"){ // Si le status équivaut à ""payment"" COMPLETED !

				$status = "<b>La commande à été payée !</b>";
			}

			echo '<tr>';

			echo '<td><a href="javascript:void(0)" id="PI_button" class="'.$sql_commande['id_transaction'].'">Afficher</a></td>	

				<td><a href="javascript:void(0)" id="articles_button" class="'.$sql_commande['id_transaction'].'">'.$count.' article(s)</a></td>';

			echo '	<td>'.$sql_commande['user'].'</td>

					<td>'.$sql_commande['date'].'</td>

			    	<td>'.$sql_commande['id_transaction'].'</td>';
			    	
			echo '
			    	<td>'.$status.'</td>

			    	<td><a href="panel_admin?menu=commande&mod='.$sql_commande['id_transaction'].'">Marquer comme "envoyer" !</a></td>

			   </tr>';

			echo '</table>';

			echo '<div id="hidden_PI_'.$sql_commande['id_transaction'].'" class="hidden_PI">';

			// On créer une boucle ici qui récupère les informations de livraison de l'utilisateur sur la bdd
			$reqpi = $bdd->prepare('SELECT sexe, prenom, nom, adresse, ville, postal FROM user WHERE username = :pseudo');

			$sqlpi = $reqpi->execute(array(
				'pseudo' => $sql_commande['user']
			));

			while($sqlpi = $reqpi->fetch()){

				echo '<div id="admin_pi_user">';
				if($sqlpi['sexe'] == 'homme'){
					echo 'M. ';
				}elseif($sqlpi['sexe'] == 'femme'){
					echo 'Mme. ';
				}

				echo $sqlpi['nom'].' '.$sqlpi['prenom'];

				echo '<br/><br/>';

				echo 'Adresse : '.$sqlpi['adresse'];
				echo '<br/>Ville : '.$sqlpi['ville'];
				echo '<br/>Code Postal : '.$sqlpi['postal'];

				echo '</div>';
			}
			$reqpi->closeCursor();

			echo '</div>';

			echo '<div id="hidden_articles_'.$sql_commande['id_transaction'].'" class="hidden_articles">';

			echo '<table id="viewer_init">';

			echo '<tr>';
		    echo '<th>Image</th>';
		    echo '<th>Titre de l\'article</th>';
	   		echo '</tr>';

		    foreach($unserialize_panier_commande as $id){
		   		
		   		$req_articles_commande = $bdd->prepare('SELECT * FROM shop WHERE id = :id');

		   		$sql_articles_commande = $req_articles_commande->execute(array(

		   			'id' => $id
		   		));

		   		while($sql_articles_commande = $req_articles_commande->fetch()){

		   			echo '<tr>

			    	<td><img id="image_item_panier" src="'.$sql_articles_commande['image1'].'" alt="'.$sql_articles_commande['titre'].'"/></td>

			    	<td>+1 '.$sql_articles_commande['titre'].'</td>

			    	</tr>';

		   		}
		   		$req_articles_commande->closeCursor();
			}

			echo '</table>';

			echo '</div>';

			echo '<table>';

			echo '<br/>';
		}


		echo '</div>';
		echo '</article>';

		echo '<script src="admin/js/commande.js"></script>';
	}
	
}
?>