<!DOCTYPE html>
<html>
	<!-- cerveau du site -->
    <head>
    		<!-- Global site tag (gtag.js) - Google Analytics
			<script async src="https://www.googletagmanager.com/gtag/js?id=UA-164685217-1"></script>
			<script>
			  window.dataLayer = window.dataLayer || [];
			  function gtag(){dataLayer.push(arguments);}
			  gtag('js', new Date());

			  gtag('config', 'UA-164685217-1');
			</script>-->

		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

		<link href="https://fonts.googleapis.com/css?family=Cantarell" rel="stylesheet">
		<link rel="icon" href="img/favicon.ico" />
		
		<?php
		
		// Les thèmes du site !
		// ------------------------------------------------------------
		
		// On vérifie qu'un thème n'est pas envoyer par l'url avec GET
		if(!empty($_GET['theme']) && $_GET['theme'] == 'light'){
			$_SESSION['style'] = 'light';
		}elseif(!empty($_GET['theme']) && $_GET['theme'] == 'carbon'){
			$_SESSION['style'] = 'carbon';
		}
		
		if(empty($_SESSION['style'])){ // Par défaut
			
			$_SESSION['style'] = 'carbon';

			echo '<link rel="stylesheet" href="themes/carbon.css" />';
			
			//le script qui fait la coloration sintaxyque des balises <pre><code>
			echo '<link href="inc/highlight/styles/a11y-dark.css" rel="stylesheet">';

			// Thème de jquery ui carbon
			echo '<link rel="stylesheet" href="themes/UI_carbon.css" />';
			

		}elseif(!empty($_SESSION['style']) && $_SESSION['style'] == 'carbon'){
			
			$_SESSION['style'] = 'carbon';

			echo '<link rel="stylesheet" href="themes/carbon.css" />';
			
			//le script qui fait la coloration sintaxyque des balises <pre><code>
			echo '<link href="inc/highlight/styles/a11y-dark.css" rel="stylesheet">';

			// Thème de jquery ui carbon
			echo '<link rel="stylesheet" href="themes/UI_carbon.css" />';

		
		}elseif(!empty($_SESSION['style']) && $_SESSION['style'] == 'light'){
			
			$_SESSION['style'] = 'light';

			echo '<link rel="stylesheet" href="themes/light.css" />';
			
			// Le thème qui fait la coloration sintaxyque des balises <pre><code> de highlight.js
			echo '<link href="inc/highlight/styles/rainbow.css" rel="stylesheet">';

			// Thème de jquery ui light
			echo '<link rel="stylesheet" href="themes/UI_light.css" />';
		

		} 
		// !!! \\// !!! \\// !!! \\// !!! \\// !!! \\// !!! \\// !!! \\// !!! \\// !!! \\// !!! \\// !!! \\
		
		// --------------------------------------------------------------------------------------------------
		// Puis on affiche quelques librairie "javascript". Ci dessous... et on sort de php -> 

		// On ajoute la feuilles de style animate css (voir : daneden.github.io/animate.css/)
		echo '<link rel="stylesheet" href="themes/plugin/animate.css"/>'; ?>
		
		<!-- jquery -->
		<script src="inc/js/plugin/jquery.min.js"></script>

		<!-- jquery ui -->
		<script src="inc/js/plugin/jquery-ui.min.js"></script>

		<!-- plugin jquery.redirect.js -->
		<script src="inc/js/plugin/jquery.redirect.js"></script>

		<!-- plugin gsap -->
		<script src="inc/plugin/gsap/minified/gsap.min.js"></script>

		<!-- jquery sticky -->
		<script src="inc/js/plugin/jquery.sticky.js"></script>

		<!-- Noty.js // Pour des notifications dynamique -->
		<link href="inc/plugin/noty/noty.min.css" rel="stylesheet"/>
		<link href="inc/plugin/noty/themes/bootstrap-v4.css" rel="stylesheet"/>
		<script src="inc/plugin/noty/noty.min.js" type="text/javascript"></script>

		<!-- Viewer.js // Pour afficher des images de façons dynamique -->
		<script src="inc/js/plugin/viewer.min.js"></script>
		<script src="inc/js/plugin/jquery-viewer.min.js"></script>
		<link  href="themes/plugin/viewer.css" rel="stylesheet">
		
		<!-- Le script qui envoit le formulaire automatiquement pour le bouton qui change les thèmes -->
		<script src="inc/js/change_theme.js"></script>

		<!-- éditeur de textarea ckeditor -->
		<script src="inc/node_modules/ckeditor4/ckeditor.js"></script>
		<!-- ----------------------------- -->

		<!-- dropin.min.js -->
		<script src="inc/js/plugin/dropin.min.js"></script>

		<!-- le script qui fait la coloration sintaxyque des balises <pre> et <code> -->
   		<script src="inc/plugin/highlight/highlight.pack.js"></script>
   		<script>hljs.initHighlightingOnLoad();</script>
		<!-- ----------------------------- -->

		<!-- Ci dessous, le format mobile du site -->
		<!--<meta name="viewport" content="width=device-width, initial-scale=0"/>-->
		<link rel="stylesheet" href="themes/mobile.css" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- ------------------------------------------------------------- -->

		<?php 

			// On récupere la fonction get_ip
			require('inc/func/func_get_ip.php');
			// On met l'ip du visiteur dans une variable
			$ipvisitor = get_ip();

			// On récupere la fonction connectSql();
			require('inc/func/func_connectsql.php');
			$bdd = connectSql();
			
			// On récupere la blacklist pour savoir quel ip est bannis...
			$reqip = $bdd->query('SELECT * FROM blacklist');
			while($sqlip = $reqip->fetch()){
				if($ipvisitor == $sqlip['ip']){
					echo '<p id="annonce">Vous êtes bannis ! Pour la raison suivante : <br/><br/>'.$sqlip['raison'].'</p>';
					$reqip->closeCursor();
					exit;
				}
			}
			$reqip->closeCursor();
		?>
    </head>
	<!-- (fin) cerveau du site -->