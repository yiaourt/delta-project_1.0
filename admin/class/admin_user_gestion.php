<?php 
	class user_gestion{
//	// Je fait une fonction qui suprimmera les utilisateurs par pseudonyme
		public function deleteUser(){
			$bdd = connectSql();
			if(!empty($_GET['delete'])){
				if(!empty($_GET['a'])){
					try{
						// set the PDO error mode to exception
						$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

						// sql to delete a record
						$sql = $bdd->prepare("DELETE FROM user WHERE username= ?");

						// use exec() because no results are returned
						$sql->execute(array($_GET['delete']));
						echo "<p id='annonce'>L'utilisateur ". $_GET['delete']." à bien été suprimmer.";
						echo "<br /> <a href='panel_admin.php?menu=user'>Rafraichir la page</a></p>";
					}
						
					catch(PDOException $e){
						echo $sql . "<br />" . $e->getMessage();
					}
					$sql->closeCursor();
				}else{
					echo "<p id='annonce'>Voulez-vous réellement supprimer ". $_GET['delete'] ." ?<br/><br/>";
					echo '<a href="panel_admin.php?menu=user&delete='.$_GET['delete'].'&a=y">Oui</a> |-| <a href="panel_admin.php?menu=user">Non</a><p/>';
				}
			}
		}

//	// Je fait une fonction qui recherchera les utilisateurs par pseudonyme
		public function searchUser(){
			$bdd = connectSql();
			// J'affiche les balises html formulaire
			echo '<form action="panel_admin.php?menu=user&search=1" method="post">';
			echo '<p id="center">Recherche de pseudo : ';
			echo '<input type="text" name="searchuser" /><input type="submit" value="Rechercher" /></p>';
			echo '</form>';

			// Je fais un if qui testera si on à fais une recherche ou non
			if(isset($_GET['search'])){
				// puis un if qui testera si le formulaire de recherche est bien remplis
				if(!empty($_POST['searchuser']))
				{
					// Je fais ici une recherche d'utilisateur en utilisant "LIKE"
					$reponse = $bdd->prepare('SELECT * FROM user WHERE username LIKE ?');
					$reponse->execute(array("%$_POST[searchuser]%"));
					// J'affiche le(s) résultat(s)
					echo '<p>Voici le(s) résultat(s) de votre recherche :</p>';
					while($donnees = $reponse->fetch())
					{
						echo '<p class="center"><span class="underline">Id : </span>' . $donnees['id'] . '
							<strong><span class="underlinered">User : </span>' . $donnees['username'] . '
							</strong><span class="underline">level : </span>' . $donnees['level'];
						echo ' <a href="panel_admin.php?ban='.$donnees['username'].'">Bannir l\'ip</a> | ';
						echo ' <a href="panel_admin.php?menu=user&delete='.$donnees['username'].'">Supprimer</a> | ';
						echo ' <a href="panel_admin.php?menu=user&id='.$donnees['id'].'">Modifier</a>';
						echo '</p>';
						echo '<div id="littleborder"></div>';
					}
					
					// Puis je compte le nombre de recherche trouver
					$rep = $bdd->prepare('SELECT COUNT(*) AS nb_users_search FROM user WHERE username LIKE ?');
					$rep->execute(array("%$_POST[searchuser]%"));
					$donnees = $rep->fetch();
					$totalsearchuser = $donnees['nb_users_search'];
					//Que j'affiche ensuite dans une variable
					echo '<p>'. $totalsearchuser .' Résultat(s) trouver !</p>';
					
					$rep->closeCursor(); // On n'oublie pas de fermer la connexion à PDO
					$reponse->closeCursor(); // On n'oublie pas de fermer la connexion à PDO
				}else{
					echo '<p>Erreur: Aucun résultat(s) trouver pour la recherche</p>';
				}
				echo '<div id="border"></div>';
			}
		}
		
//	// On créer ensuite la liste d'utilisateurs avec une fonction pagination!
		public function listUser(){
			$bdd = connectSql();
			echo "<p class='titlecenter'>Liste d'utilisateurs</p>";
			// On met dans une variable le nombre d'user qu'on veut par page
			$nombreUsersParPage = 15; // on met un total d'users qui s'affichent par page
			
			// On récupère le nombre total d'user
			$retour = $bdd->query('SELECT COUNT(*) AS nb_users FROM user');
			$donnees = $retour->fetch();
			$totalUsers = $donnees['nb_users'];
			
			// On calcule le nombre d'utilisateur à créer
			$nombreDePages  = ceil($totalUsers / $nombreUsersParPage);
			
			// Puis on fait une boucle pour écrire les liens vers chacune des pages
			echo 'Page : ';
			for ($i = 1 ; $i <= $nombreDePages ; $i++)
			{
				echo '<a href="panel_admin.php?menu=user&page=' . $i . '">' . $i . '</a> ';
			}
			
			// on affiche les messages

			if (isset($_GET['page']))
			{
					$page = $_GET['page']; // On récupère le numéro de la page indiqué dans l'adresse (panel_admin.php?menu=user&&page=?)
			}

			else // La variable n'existe pas, c'est la première fois qu'on charge la page
			{
				$page = 1; // On se met sur la page 1 (par défaut)
			}
			
			// On calcule le numéro du premier message qu'on prend pour le LIMIT de MySQL
			$premierUserAafficher = ($page - 1) * $nombreUsersParPage;
			$reponse = $bdd->query('SELECT * FROM user ORDER BY id DESC LIMIT ' . $premierUserAafficher . ', ' . $nombreUsersParPage);

			while ($donnees = $reponse->fetch())
			{
				echo '<p class="center"><span class="underline">Id : </span>' . $donnees['id'] . '
					<span class="underline">Ip : </span>' . $donnees['ip'] . '
					<strong><span class="underlinered">User : </span>' . $donnees['username'] . '
					</strong><span class="underline">level : </span>' . $donnees['level'];
				echo ' <a href="panel_admin.php?ban='.$donnees['username'].'">Bannir l\'ip</a> | ';
				echo ' <a href="panel_admin.php?menu=user&delete='.$donnees['username'].'">Supprimer</a> | ';
				echo ' <a href="panel_admin.php?menu=user&id='.$donnees['id'].'">Modifier</a>';
				echo '</p>';
				echo '<div id="littleborder"></div>';
			}
			
			$reponse->closeCursor(); // On n'oublie pas de fermer la connexion à PDO
			echo '</div>'; 
		}
	}
?>