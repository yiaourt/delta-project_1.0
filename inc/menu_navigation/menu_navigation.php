<?php 
	
	if(empty($_SESSION['style'])){ // Par défaut
			
			echo '<a id="logo" href="index.php"><img class="logo" src="img/titlecarbon.png" alt="icone delta"></a>';
			

		}elseif(!empty($_SESSION['style']) && $_SESSION['style'] == 'carbon'){
			
			echo '<a id="logo" href="index.php"><img class="logo" src="img/titlecarbon.png" alt="icone delta"></a>';

		
		}elseif(!empty($_SESSION['style']) && $_SESSION['style'] == 'light'){
			
			echo '<a id="logo" href="index.php"><img class="logo" src="img/titlelight.png" alt="icone delta"></a>';

		} 

	// On affiche les liens du site ici, on vérifie aussi ou est l'utilisateur avec la variable session WIU (where is user)
	echo '<ul id="nav">';

	echo '<li ';if($_SESSION['WIU'] == 'forum'){echo 'id="nav_visited">';
			}else{ echo 'id="nav1" onmouseover="ChangeID()" onmouseout="reChangeID()">';}

		   	echo '<a href="forum.php">Forum</a></li>';
		
	echo '<li ';if($_SESSION['WIU'] == 'shop'){echo 'id="nav_visited">';}else{ echo 'id="nav2" onmouseover="ChangeID()" onmouseout="reChangeID()">';}
		   	echo '<a href="shop.php">Shop 3D</a></li>';

	echo '<li ';if($_SESSION['WIU'] == 'irc'){echo 'id="nav_visited">';}else{ echo 'id="nav3" onmouseover="ChangeID()" onmouseout="reChangeID()">';}
		   	echo '<a href="irc.php">IRC</a></li>'; 

	echo '</ul>';

	echo '<script src="inc/menu_navigation/js/nav.js"></script>';

?>