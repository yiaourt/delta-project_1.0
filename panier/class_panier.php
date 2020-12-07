<?php 
class panier{

	public function history(){
		$bdd = connectSql();

		// Cette fonction sert à afficher l'historique des commandes de l'utilisateur.

		// On affiche la base du tableau
		echo '<article>';

		echo '<a href="panier.php"><- Retour</a>';

		echo '<div id="menumarg">';
		echo '<h2><u>Votre historique de commandes :</u></h2>';

		echo '<table>';

		echo '<tr>';
	    echo '<th>Article(s)</th>';
	    echo '<th>Date</th>';
	    echo '<th>ID</th>';
	    echo '<th>Statuts</th>';
   		echo '</tr>';

   		// Puis dans une boucle on affiche chaques commandes
		$req_commande = $bdd->prepare('SELECT * FROM commande WHERE user = :pseudo');

		$sql_commande = $req_commande->execute(array(

			'pseudo' => $_SESSION['pseudo']
		));

		while($sql_commande = $req_commande->fetch()){
			
			

			// On compte le nombre d'articles
			$unserialize_panier_commande = unserialize($sql_commande['articles']);

			$count = 0;

			foreach($unserialize_panier_commande as $article){
				$count = $count+1;
			}

			// On vérifie ensuite le status
			if($sql_commande['status'] == "COMPLETED"){ // Si le status équivaut à ""payment"" COMPLETED !

				$status = "Nous préparons votre commande ...";
			}

			echo '<tr>';

			echo '<td><a href="javascript:void(0)" id="articles_button" class="'.$sql_commande['id_transaction'].'">'.$count.' article(s)</a></td>';

			echo '	<td>'.$sql_commande['date'].'</td>

			    	<td>'.$sql_commande['id_transaction'].'</td>';
			    	
			echo '
			    	<td>'.$status.'</td>

			   </tr>';

			echo '</table>';

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

		}
		$req_commande->closeCursor();

		if(empty($status)){ // Si on ne reçoit aucune réponse.
			echo '</table>';

   			// On affiche un message 
			echo '<h3>Vous n\'avez aucune commandes en cours ou terminer.</h3>';
		}

		echo '</table>';

		echo '<script src="panier/js/panier.js"></script>';
	}
	
	public function verifyUser(){
		$bdd = connectSql();

		// On vérifie dans cette fonction que l'utilisateur à correctement rentrer ses informations de livraisons
		// (Nom, Prénom, Adresse, Numéro de téléphone)

		// On fait ici une informations sur error=1
		if(!empty($_GET['error'])){
			echo '<div id="annonce">Erreur dans le formulaire d\'envois, veuillez réessayer.</div>';
		}

		echo '<div id="menumarg">';

		echo '<h1>Vérifier vos informations de livraison : </h1>';

		echo '<div id="information_personnel">';

		// On créer une boucle ici qui récupère les informations de livraison de l'utilisateur sur la bdd
		$reqpi = $bdd->prepare('SELECT sexe, prenom, nom, adresse, ville, postal FROM user WHERE username = :pseudo');

		$sqlpi = $reqpi->execute(array(
			'pseudo' => $_SESSION['pseudo']
		));

		while($sqlpi = $reqpi->fetch()){

			// Si les informations de livraison n'existe pas on fait un formulaire
			if(empty($sqlpi['sexe']) OR 
			empty($sqlpi['prenom']) OR 
			empty($sqlpi['nom']) OR 
			empty($sqlpi['adresse']) OR 
			empty($sqlpi['ville']) OR
			empty($sqlpi['postal'])){

				// On affiche donc le formulaire ci dessous.
				echo '<div id="annonce">Vous devez remplir vos informations personnel de livraisons pour procéder au paiment et à l\'envois de votre commande, <br/>assurez-vous qu\'ils sont corrects, nous ne serons pas tenu pour responsable de fausse informations de livraison.</div>';

				echo '<form action="panier.php?save=1&sendpi=1" method="post">';

				echo '<br/><br/>Sexe (*) : 

						<select name="sexe">
						  <option value=""></option>
						  <option value="homme">Homme</option>
						  <option value="femme">Femme</option>
						</select>';

				echo '<br/><br/>Prénom (*) : <input type="text" name="prenom" id="prenom"/> Nom (*) : <input type="text" name="nom" id="nom"/>';

				echo '<br/><br/>Adresse (*) : <textarea name="adresse" id="adresse"></textarea>';

				echo '<br/><br/>Ville (*) : <input type="text" name="ville" id="ville"/> Code postal (*) : <input type="text" name="postal" id="postal"/>';

				echo '<br/><br/><input type="submit" value="Enregistrer" />';

				echo '</form>';

			}else{ // Sinon les informations personnel de livraison existe...
				
				// On affiche les informations de livraison à l'utilisateur ainsi qu'un bouton modifier.
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

			}
		}
		$reqpi->closeCursor();

		echo '</div>'; // <div id="information_personnel"

		echo 'Ces informations sont-elles corrects ? <br/>';

		echo '<a id="button_end_panier" href="panier.php?save=1&sendtopaypal=1">Oui, passer au paiment et à l\'envois de ma commande.</a>';

		echo '<a id="button_end_panier" href="panier.php?save=1&mod=1">Non, je veux modifier mes informations de livraison.</a>';

		echo '</div>';
		
	}

	public function sendPI(){
		$bdd = connectSql();

		// On vérifie si le formulaire d'informations personnelle est remplis.
		if(empty($_POST['sexe']) OR 
		empty($_POST['prenom']) OR 
		empty($_POST['nom']) OR 
		empty($_POST['adresse']) OR 
		empty($_POST['ville']) OR
		empty($_POST['postal'])){ // Si il y à des variable post qui sont vide

			// Il y à donc une érreur, on renvois l'utilisateur.
			echo '<script language="Javascript">setTimeout(function(){document.location.replace("panier.php?save=1&error=1")}, 600);</script>';
			exit;

		}

		// Ci dessous on fait une fonction qui permet d'envoyer les informations personnel de l'utilisateur sur la base de données.

		$reqpiuser = $bdd->prepare('UPDATE user SET sexe = :sexe, prenom = :prenom, nom = :nom, adresse = :adresse, ville = :ville, postal = :postal WHERE username = :pseudo');

		$reqpiuser->execute(array(

			'sexe' => $_POST['sexe'],
			'prenom' => $_POST['prenom'],
			'nom' => $_POST['nom'],
			'adresse' => $_POST['adresse'],
			'ville' => $_POST['ville'],
			'postal' => $_POST['postal'],
			'pseudo' => $_SESSION['pseudo']
		));

		$reqpiuser->closeCursor();

		echo '<div id="information">Informations de livraison enregistrer avec succés. <br/>Vous allez être rediriger...</div>';
		echo '<script language="Javascript">setTimeout(function(){document.location.replace("panier.php?save=1")}, 2900);</script>';
		
		exit;
	}

	public function modPi(){

		echo '<div id="information">';

		echo '<h1 id="floatleft">Formulaire d\'informations personnel de livraison :</h1>';

				// On affiche donc le formulaire ci dessous.
				echo '<br/><br/><br/><div id="annonce">Vous devez remplir vos informations personnel de livraisons pour procéder au paiment et à l\'envois de votre commande, <br/>assurez-vous qu\'ils sont corrects, nous ne serons pas tenu pour responsable de fausse informations de livraison.</div>';

				echo '<a href="panier.php?save=1"><- Retour</a>';

				echo '<form action="panier.php?save=1&sendpi=1" method="post">';

				echo '<br/><br/>Sexe (*) : 

						<select name="sexe">
						  <option value=""></option>
						  <option value="homme">Homme</option>
						  <option value="femme">Femme</option>
						</select>';

				echo '<br/><br/>Prénom (*) : <input type="text" name="prenom" id="prenom"/> Nom (*) : <input type="text" name="nom" id="nom"/>';

				echo '<br/><br/>Adresse (*) : <textarea name="adresse" id="adresse"></textarea>';

				echo '<br/><br/>Ville (*) : <input type="text" name="ville" id="ville"/> Code postal (*) : <input type="text" name="postal" id="postal"/>';

				echo '<br/><br/><input type="submit" value="Enregistrer" />';

				echo '</form>';

				echo '</div>';

	}

	public function startTransaction(){
		$bdd = connectSql();

		echo '<article>';
		echo '<div id="menumarg">';

		echo '<h3><u>Choisissez votre méthode de paiement :</u></h3>';

   		// On vérifie qu'il y à des article dans le panier
   		if(!empty($_SESSION['panier'])){

   			// On compte le nombre d'articles
	   		$count = 0;

			// On affiche chaques articles du panier 1 à 1 dans une boucles
			foreach($_SESSION['panier'] as $nbr => $id){

				// On incrémente count
				$count = $count+1;

			    $req_panier = $bdd->prepare('SELECT * FROM shop WHERE id=:id');

			    $sql_panier = $req_panier->execute(array(
			    	'id' => $id
			    ));

			    while($sql_panier = $req_panier->fetch()){
			   
			    	//On met le prix dans une variable array.
			    	$multi_prix[] = $sql_panier['prix'];
			    }
			    $req_panier->closeCursor();
			}

			// On calcul le prix total
			foreach($multi_prix as $prix){

				if(!empty($first)){

					$total = $total + $prix;
				
				}else{

					$total = $prix;

					$first = 'ok'; 
				}
			}

			// On affiche le total des commandes
			echo '<h3>'.$count.' Article(s) pour un total de : <b>'.$total.' €</b></h3>';

			// On récupere les informations de livraisons de l'utilisateur sur la bdd.
			$reqpi = $bdd->prepare('SELECT sexe, prenom, nom, adresse, ville, postal FROM user WHERE username = :pseudo');

			$sqlpi = $reqpi->execute(array(
				'pseudo' => $_SESSION['pseudo']
			));

			while($sqlpi = $reqpi->fetch()){

				$sexe = $sqlpi['sexe'];

				$prenom = $sqlpi['prenom'];

				$nom = $sqlpi['nom'];

				$adresse = $sqlpi['adresse'];

				$ville = $sqlpi['ville'];

				$postal = $sqlpi['postal'];

			}
			$reqpi->closeCursor();


			echo '<div style="position: relative; z-index: 1;">
					  <div id="paypal-button-container"></div>
				</div>

<script src="https://www.paypal.com/sdk/js?client-id=AXMeRQJKd2yI9-EmIyj0eW6_AGgjZVjg1knhRdzNf2PeEpVIw8oZ5f0pRZ_st1kkCugqjvc1o7NMh3tQ&locale=fr_FR&currency=EUR"></script>
<script>
  paypal.Buttons({
      style: {
          shape: "rect",
          color: "black",
          layout: "vertical",
          label: "pay",
          
      },
      createOrder: function(data, actions) {
          return actions.order.create({
	        payer: {
		        name: {
		          given_name: "'.$prenom.'",
		          surname: "'.$nom.'"
		        },
		        address: {
		          address_line_1: "'.$adresse.'",
	              address_line_2: "",
	              admin_area_2: "'.$ville.'",
	              admin_area_1: "",
	              postal_code: "'.$postal.'",
	              country_code: "FR"
		        },
		        email_address: "customer@domain.com"
		      },
            purchase_units: [{
                  amount: {
                      value: "'.$total.'",
                      currency_code: "EUR"
                  },
                  shipping: {
		            address: {
		              address_line_1: "'.$adresse.'",
		              address_line_2: "",
		              admin_area_2: "'.$ville.'",
		              admin_area_1: "",
		              postal_code: "'.$postal.'",
		              country_code: "FR"
		            }
		          },
              }]
          });
      },
      onApprove: function(data, actions) {
          return actions.order.capture().then(function(details) {
               

               if(details.status == "COMPLETED"){
               	//console.log(details.id + details.status);

               	var id_transaction = details.id;

               	var status_transaction = details.status;
               	
               	$.redirect("panier.php?success=1", {id: id_transaction, status: status_transaction, sexe: "'.$sexe.'", nom: "'.$nom.'", prenom: "'.$prenom.'", adresse: "'.$adresse.'", ville: "'.$ville.'", postal: "'.$postal.'"});

               	}else{
               		alert("Erreur lors de la transaction.");

               	}
          });
      }
  }).render("#paypal-button-container");
</script>';



			echo '</div>';
			echo '</article>';
		
		}
	}

	public function finish_transaction(){
		$bdd = connectSql();

		// La fonction ci dessous sert à terminer la transaction de l'utilisateur et à ajouter la commande à la liste des commandes payer qui doivent être envoyer qui seront visibles sur le panel admin.
		// -----------------------------------------------------------------------------------------------------------
	
		// On vérifie bien que les variables $_POST sont renvoyer.
		if(!empty($_POST['id']) && 
		!empty($_POST['status']) &&
		!empty($_POST['sexe']) &&
		!empty($_POST['nom']) &&
		!empty($_POST['prenom']) &&
		!empty($_POST['adresse']) &&
		!empty($_POST['ville']) &&
		!empty($_POST['postal'])){

			// On vérifie que les données ne sont pas envoyer une 2eme fois sur la bdd
			$req_verif_commande = $bdd->prepare('SELECT * FROM commande WHERE id_transaction = :post_id');

			$sql_verif_commande = $req_verif_commande->execute(array(

				'post_id' => $_POST['id']
			));

			while($sql_verif_commande = $req_verif_commande->fetch()){

				if($_POST['id'] == $sql_verif_commande['id_transaction']){

					echo '<br/><br/><div id="annonce">Désoler une érreur est survenu.</div>';

					exit;
				}
			}
			$req_verif_commande->closeCursor();

			// si tous c'est bien passer on envoit la commande sur la bdd

			$reqnew_commande = $bdd->prepare('INSERT INTO commande(id_transaction, status, articles, user, sexe_user, nom_user, prenom_user, adresse_user, ville_user, postal_user, date) VALUES(:id_transaction, :status, :articles, :user, :sexe_user, :nom_user, :prenom_user, :adresse_user, :ville_user, :postal_user, NOW())');


			$reqnew_commande->execute(array(

				'id_transaction' => $_POST['id'],
				'status' => $_POST['status'],
				'articles' => serialize($_SESSION['panier']),
				'user' => $_SESSION['pseudo'],
				'sexe_user' => $_POST['sexe'],
				'nom_user' => $_POST['nom'],
				'prenom_user' => $_POST['prenom'],
				'adresse_user' => $_POST['adresse'],
				'ville_user' => $_POST['ville'],
				'postal_user' => $_POST['postal']

			));
			$reqnew_commande->closeCursor();

			// On vide ensuite le panier de l'utilisateur.
			unset($_SESSION['panier']);

			// On vide aussi le panier de la base de données
			$reqpanier_user = $bdd->prepare('UPDATE user SET panier = "" WHERE username = :pseudo');


			$reqpanier_user->execute(array(

				'pseudo' => $_SESSION['pseudo']

			));

			$reqpanier_user->closeCursor();


			// et on affiche un message.
			echo '<br/><br/><article><div id="menumarg"><div id="information">Félicitations ! Votre commande à bien était créer et sera envoyer dans les plus brefs délais, vous pouvez accéder à <a href="panier.php?history=1">l\'historique de commandes</a> dans votre panier pour voir l\'état de la commande, merci d\'avoir choisi delta-project!</div></div></article>';
			
			exit;
		
		}else{

			echo '<br/><br/><div id="annonce">Désoler une érreur est survenu veuillez contacter au plus vite un administrateur.</div>';
			exit;
			
		}
	}

}


?>