<?php session_start(); ?>
<?php require('inc/head_head.php'); ?>

	<!-- tête du site -->
	<header>
	<?php // On déclare ou est l'utilisateur sur le site
		$_SESSION['WIU'] = 'messagerie';

		require("inc/header_menu.php"); ?>
	</header>
	<!-- tête du site -->
	
	<!-- corps du site -->
    <body>
	<?php

		// On appelle la barre de menu au top qui s'anime avec scrollMagic. 
		$nav->nav_barre_top(); //(fonction qui fait parti de la classe "menu_navigation.php")

		//////////////////////////////////
		// On créer les classes de la messagerie.
		
		// La classe qui affiche la boite de réception.
		require('messagerie/aff_BR.php');
		$aff_BR = new aff_BR;
		
		// la classe qui affiche un message les commentaires l'envois de commentaires aussi au message
		require('messagerie/aff_MSG.php');
		$aff_msg = new aff_MSG;
		
		/* La classe aff_sous_forum
		require('forum/sous_forum/aff_sous_forum.php');
		$aff_sous_forum = new aff_sous_forum;
		
		// La classe topic
		require('forum/sous_forum_topic/topic.php');
		$topic = new topic;
		
		// La classe comment
		require('forum/sous_forum_topic/comment.php');
		$comment = new comment;
		
		// La classe forum_admin
		require('forum/admin/forum_admin.php');
		$forum_admin = new forum_admin; */
		
		///////////////////////////////////////////////////////
		// On conditione maintenant toutes les pages de la messagerie
		if(empty($_SESSION['pseudo'])){
			$aff_BR->error_session();
			exit;
		}else{
			if(empty($_GET['id'])){
				if(!empty($_GET['new'])){
					$aff_BR->newMsg(); // Créer un nouveau message
					exit;
				}elseif(!empty($_GET['sendMSG'])){
					if(empty($_POST['post_objet'])){ //erreur objet vide
						$aff_BR->errornewMSG('Erreur : Le titre du message est vide. Vous allez être rediriger ...');
					}elseif(empty($_POST['post_user_distant'])){ // erreur contacts vide
						$aff_BR->errornewMSG('Erreur : Veuillez entrer un contact ou cliquer sur un contact dans la liste de contacts.<br/> Vous allez être rediriger ...');
					}else{
						$aff_BR->sendMsg();
						exit;
					}
				}elseif(!empty($_GET['delete'])){
					if(empty($_POST['del'])){
						if(intval($_GET['delete'])){ 
							$aff_BR->deleteMSGBR($_GET['delete']);
							exit;
						}else{
							$aff_BR->error('Erreur : Vous devez cocher vos messages. <br/> Vous allez être rediriger ...');
						}
					}else{
						foreach($_POST['del'] as $msgid){
							$aff_BR->deleteMSGBR($msgid);
						}
						exit;
					}
				}elseif(!empty($_GET['mod']) && intval($_GET['mod'])){
					if(empty($_GET['sendMOD'])){
						$aff_BR->modMSGBR($_GET['mod']);
					}else{
						$aff_BR->sendMOD($_GET['mod']);
					}
				}else{
					$aff_BR->afficheBR(); // Affiche la boite de réception.
					exit;
				}
			}else{ // Si $_GET['id'] id existe on se trouve alors dans le message.
			
				if(empty($_GET['msg'])){
					$aff_msg->afficheMSG();
					exit;
				}elseif($_GET['msg'] == 'new_comment'){
					if(empty($_POST['comment'])){
						$aff_msg->errorRefer('Erreur : Vous devez remplir l\'éditeur de texte pour laisser un commentaire. <br/> Vous allez être rediriger ...');
					}else{
						$aff_msg->newCOM();
					}
				}elseif($_GET['msg'] == 'mod_comment'){
					if(empty($_GET['sendMOD'])){
						$aff_msg->modCOM($_GET['id']);
					}else{
						if(empty($_POST['post_comment'])){
							$aff_msg->errorRefer('Erreur : Vous devez remplir l\'éditeur de texte pour laisser un commentaire. <br/> Vous allez être rediriger ...');
						}else{
							$aff_msg->sendMODcom($_GET['id']);
						}
					}
				}elseif($_GET['msg'] == 'delete_comment'){
					$aff_msg->deleteCOM($_GET['id']);
				}
			}
		}
		
		
	?>
    </body>
	<!-- (fin) corps du site -->
	
	<!-- bas du site. -->
	<footer><?php
	require('inc/notif_is_new.php');
	$notification = new notification;
	
	if($_SESSION['msg_is_new'] == 1){
		$notification->R0();
	}
	?></footer>
</html>