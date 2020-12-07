<?php 
class forum_admin{
	
	public function adminmoveQuestion(){
		if($_SESSION['level'] == '0'){
			if(empty($_GET['a']) || $_GET['a'] !== 'y'){
				$bdd = connectSql();
				
				// On récupere le formulaire de la page précedente contenant l'id des topics que l'on veut bouger
				// On le(s) met dans une variable SESSION
				$_SESSION['postdelmove'] = $_POST['delmove'];
				
				// On récupere l'id_s_cat des topics pour savoir quelles radio checker..
				$reqtopicid_s_cat = $bdd->prepare('SELECT id_s_cat FROM sous_forum_topic WHERE id = :idtopic');
				$sqltopicid_s_cat = $reqtopicid_s_cat->execute(array('idtopic' => $_SESSION['postdelmove'][0]));
				while($sqltopicid_s_cat = $reqtopicid_s_cat->fetch()){
					$topicid_s_cat = $sqltopicid_s_cat['id_s_cat'];
				}
				
				echo '<form action="forum.php?topic=forum_admin&a=y" method="post">';
				echo '<div id="information">Veuillez sélectioner la sous-catégories qui recevras les topics sélectionner précedement :<br/><br/>';
				
				// On récupere l'id et le titre des sous catégorie
				$reqadfo = $bdd->query('SELECT id, titre FROM sous_forum');
				while($sqlsf = $reqadfo->fetch()){
					echo '<div id="qlqborder">';
					echo '<input type="radio" name="idnewcat" value="'.$sqlsf['id'].'" '; if($topicid_s_cat == $sqlsf['id']){ echo 'checked';} echo '>';
					echo '<span class="underline">id = </span>'.$sqlsf['id'].' | <span class="underline">Catégorie = </span>'.$sqlsf['titre'].'</div>';
				}
				echo '<input type="submit" name="movelikejagger" value="Déplacer !" />';
				echo '</div>';
				exit;
			}
		}else{
			echo '<div id="annonce">Erreur: Accés interdit.</div>';
			echo '<a href="forum.php">Retour ...</a>';
			exit;
		}
	}
	
	public function adminmoveTopic(){
		if(!empty($_GET['a']) && $_GET['a'] == 'y'){
			if($_SESSION['level'] == '0'){
				$bdd = connectSql();
				
				foreach($_SESSION['postdelmove'] as $topicid){
					$requpsft = $bdd->prepare('UPDATE sous_forum_topic SET id_s_cat = :postidnewcat WHERE id = :topicid');

					$requpsft->execute(array(
						'postidnewcat' => $_POST['idnewcat'],
						'topicid' => $topicid
					));
					
					echo '<p id="information">Sujet <span class="underline">id: </span>'.$topicid.' déplacer !</p>';
				}
				$requpsft->closeCursor();
			}else{
				echo '<div id="annonce">Erreur: Accés interdit.</div>';
				echo '<a href="forum.php">Retour ...</a>';
				exit;
			}
		}
	}
	
	public function admindeleteTopic(){
		if($_SESSION['level'] == '0'){
			$bdd = connectSql();
			foreach($_POST['delmove'] as $topicid)
			{
				try{
					// set the PDO error mode to exception
					$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					// sql to delete a record-
					$reqdel = $bdd->prepare("DELETE FROM sous_forum_topic WHERE id= ?");
					$reqdel->execute(array($topicid));
					$reqdel->closeCursor();
					$reqdel2 = $bdd->prepare("DELETE FROM topic_comment WHERE topic_id= ?");
					$reqdel2->execute(array($topicid));
					$reqdel2->closeCursor();
					echo "<br/><p id='annonce'>Le topic id n°".$topicid." à bien était suprimmer ainsi que tous les commentaires présent.";
				}
				catch(PDOException $e){
					echo $reqdel . "<br />" . $e->getMessage();
					echo $reqdel2  . "<br />" . $e->getMessage();
				}
				echo "<br /> <a href='forum.php'>Rafraichir la page</a></p>";
				$reqdel->closeCursor();
				$reqdel2->closeCursor();
			}
			exit;
		}else{
			echo '<div id="annonce">Erreur: Accés interdit.</div>';
			echo '<a href="forum.php">Retour ...</a>';
			exit;
		}
	}
}

?>