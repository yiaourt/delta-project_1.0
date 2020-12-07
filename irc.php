<?php session_start(); ?>
<?php require('inc/head_head.php'); ?>

	<!-- tête du site -->
	<?php // On déclare ou est l'utilisateur sur le site
		$_SESSION['WIU'] = 'irc';

		require("inc/header_menu.php"); ?>

	
	<body>
		<?php 

		// On appelle la barre de menu au top qui s'anime avec scrollMagic. 
		$nav->nav_barre_top(); //(fonction qui fait parti de la classe "menu_navigation.php")	

    	echo '<article>';

		// On vérifie que l'utilisateur est bien connecté
		if(empty($_SESSION['pseudo'])) {
			echo '<br/>';
			echo '<div id="menumarg">';
			echo '<div id="center">';
			echo 'Vous devez être connecter pour pouvoir accéder à la messagerie instantanée, <a href="inscription.php">inscrivez-vous</a> ou <a href="connexion.php">connectez-vous</a>';
			echo '</div></div>';
			exit;
		
		}else{

			// Le titre
			echo 'Messagerie instantanée :<hr/><br/>';

			// Un bouton pour rafraichir le tchat...
			echo '<a href="irc/history.php"><- Historique</a><br/>';



			// La conversation
			echo '<div id="conversation">';
            echo '</div>';



			// Les utilisateurs en ligne
			echo '<span id="css_useronline"><u>Utilisateurs en ligne :</u><hr/><br/>

			<span id="useronline"></span>';

			echo '</span>';
			
			// Le formulaire d'envois du message.
			echo'<hr/><div id="envoimessage"><form method="POST" action="irc/send.php">
											        
											        <input type="text" id="message" size="75">
											        <input type="submit" name="submit" value="Envoyez !" id="envoyer" />
											      
											      </form>';

			if($_SESSION['level'] == 0) { // Si l'utilisasteur est admin, on affiche le panel admin du tchat
				echo '<div id="ircadmin">Panel Admin : <br/><br/>
				
					<a href="irc/admin.php?delete=1">Réinitialiser le tchat</a>
				
				</div>';
			}
		}
		echo '</article>';

		// On ajoute le script qui boucle le tchat à un rafraichissement permanent
		echo '<script src="inc/js/irc.js"></script>';
	?>
    </body>

    <!-- Un script qui vérifie la connexion de l'utilisateur actuelle dans une boucle 
			et qui renvois dans la base de données 0 ou 1 au tableau is_connect de la table user -->
	<script src="inc/js/is_connect.js"></script>
	
	<!-- bas du site. -->
	<footer>
		<br/><br/>© 2020 Delta Project
	</footer>
</html>
