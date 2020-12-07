<?php session_start(); ?>
<?php // cerveau du site
require('inc/head_head.php'); ?>

	<!-- tête du site -->
	<?php 
		// On déclare ou est l'utilisateur sur le site
		$_SESSION['WIU'] = 'forum';

		require("inc/header_menu.php"); ?>
	<!-- tête du site -->
	
	<!-- corps du site -->
    <body>
	<?php 

		// On appelle la barre de menu au top qui s'anime avec scrollMagic. 
		$nav->nav_barre_top(); //(fonction qui fait parti de la classe "menu_navigation.php")

		echo'<br><hr>';

		//////////////////////////////////
		// On apelle les classes du forum :
		
		// La classe aff_forum.
		require('forum/forum/aff_forum.php');
		$aff_forum = new aff_forum;
		
		// La classe aff_sous_forum.
		require('forum/sous_forum/aff_sous_forum.php');
		$aff_sous_forum = new aff_sous_forum;
		
		// La classe topic.
		require('forum/sous_forum_topic/topic.php');
		$topic = new topic;
		
		// La classe comment.
		require('forum/sous_forum_topic/comment.php');
		$comment = new comment;
		
		// La classe forum_admin.
		require('forum/admin/forum_admin.php');
		$forum_admin = new forum_admin;
		
		// Une classe pour les notifications.
		require('inc/notification.php'); // J'ajoute le système de notifications qui réinitialise la variable $_SESSION['lastdateTP']
		$notif = new Notif;
		// --------------------------------------------------------------------------------------------------

		echo '<div id="backgforum">'; // le bloc div css background du forum.
		
		if(!empty($_GET['Rz'])){
			$notif->R0(); // Remet à zéro les alertes notifications du forum.
		}
		
		///////////////////////////////////////////////////////
		// On conditione maintenant toutes les pages du forum.
		if(empty($_GET['id'])){
			if(!empty($_GET['topic']) && $_GET['topic'] == 'forum_admin'){
				if(!empty($_POST['delete'])){
					$forum_admin->admindeleteTopic();
				}
				elseif(!empty($_POST['move'])){
					$forum_admin->adminmoveQuestion();
				}
				elseif(!empty($_POST['idnewcat'])){
					$forum_admin->adminmoveTopic();
				}
				else{
					echo '<p id="annonce">Erreur : aucune id de sous-catégorie n\'à était envoyer</p>';
				}
			}else{
				if(empty($_GET['topic'])){
					
					// Si la variable "$_SESSION['lastdateTP']" existe l'utilisateur est connecté. (contient un tableau "id_sous_forum" => "last date topic" pour les notifications.)
					if(isset($_SESSION['lastdateTP'])) {
						
						$notif->initialize_TPalert(); // L'initialisateur de variables d'alertes de notifications de nouveaux sujets.
						$notif->initialize_CMalert(); // L'initialisateur de variables d'alertes de notifications de nouveaux commentaires.
						
						// l'affichage du tableau des dernière dates à afficher si besoin. ("topic_id" => "date dernier commentaire")
						
						//echo '<br/><br/><u>variable SESSION lastdateCM :</u>'; 
						//print_r($_SESSION['lastdateCM']);
						
						// l'affichage du tableau des dernière dates à afficher si besoin. ("id sous forum" => "date dernier topic")
						
						//echo '<br/><br/><u>variable SESSION lastdateTP :</u>'; 
						//print_r($_SESSION['lastdateTP']);
						
						echo '<div id="rzbutton"><a href="forum.php?Rz=1">Réinitialiser les notifications du forum.</a></div>'; // le bouton pour réinitialiser les notifications.
					}

					$aff_forum->afficheForum(); // On affiche le forum
					
					
				}
			}
		}else{

			if(!empty($_GET['topic']) && $_GET['topic'] == 'new'){ 
				if(!empty($_GET['id']) && !empty($_GET['submit']) && $_GET['submit'] == '1') {
					$topic->submitTopic();
				}else{
					$topic->newTopic();
				}
			}
			elseif(!empty($_GET['topic']) && $_GET['topic'] == 'new_comment' && !empty($_GET['submit']) && $_GET['submit'] == '1'){
				$comment->submitComment();
			}
			elseif(!empty($_GET['topic']) && $_GET['topic'] == 'mod'){
				if(!empty($_GET['id']) && !empty($_GET['submit']) && $_GET['submit'] == '1'){
					$topic->submitmodTopic();
				}else{
					$topic->modTopic();
				}
			}
			elseif(!empty($_GET['topic']) && $_GET['topic'] == 'mod_comment'){
				if(!empty($_GET['id']) && !empty($_GET['submit']) && $_GET['submit'] == '1'){
					$comment->submitmodComment();
				}else{
					$comment->modComment();
				}
			}
			elseif(!empty($_GET['topic']) && $_GET['topic'] == 'delete_comment'){
				$comment->deleteComment();
			}
			elseif(!empty($_GET['topic']) && $_GET['topic'] == 'delete'){
				$topic->deleteTopic();
			}
			elseif(!empty($_GET['topic'])){
				// Si la variable "$_SESSION['lastdateTP']" existe l'utilisateur est connecté. (contient un tableau "id_sous_forum" et "last date topic".)
				if(isset($_SESSION['lastdateTP'])) {
					$notif->R0_topic($_GET['topic']); // Réinitialise la notification utilisateur du topic.
					$notif->R0_comment($_GET['topic']);
					// print_r($_SESSION['lastdateTP']); // l'affichage du tableau des dernière dates à afficher, si besoin.
				}
				$topic->afficheTopic(); 
			}
			else{
				// Si la variable "$_SESSION['lastdateTP']" existe l'utilisateur est connecté. (contient un tableau "id_sous_forum" => "last date topic" pour les notifications.)
				if(isset($_SESSION['lastdateTP'])) {
					$notif->initialize_TPalert(); // L'initialisateur de variables d'alertes de notifications de nouveaux sujets.
					$notif->initialize_CMalert(); // L'initialisateur de variables d'alertes de notifications de nouveaux commentaires.
					
					//print_r($_SESSION['lastdateCM']); // l'affichage du tableau des dernière dates à afficher si besoin. ("topic_id" => "date dernier commentaire")
					
					//print_r($_SESSION['lastdateTP']); // l'affichage du tableau des dernière dates à afficher si besoin. ("id sous forum" => "date dernier topic")
					
					echo '<div id="rzbutton"><a href="forum.php?Rz=1">Réinitialiser les notifications du forum.</a></div>'; // le bouton pour réinitialiser les notifications.
				}
				$aff_sous_forum->affichesousForum();
			}
		}
		
		
	echo '</div>'; // '<div id="backgforum">'; 

	// Script qui scroll au forum 
	echo '<script src="inc/js/forum.js"></script>'; ?>
    </body>
	<!-- (fin) corps du site -->

	<footer>
		<br/><br/>© 2020 Delta Project
	</footer>
</html>