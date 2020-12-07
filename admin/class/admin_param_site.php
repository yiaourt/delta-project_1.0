<?php class param_site{
	
	// // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 
	// La page d'accueil, ci-dessous
	public function afficheParam(){
		$bdd = connectSql();
		
			echo '<div id="menumarg">';
			echo '<u>Onglets Spéciaux Sujets:</u> <hr width=\"100%\" size=\"1\" />';
			echo 'Description : Créer/Modifie/Détruit un-dés onglets spéciaux, d\'un titre de sujet.';
			echo '<div id="bordernomarg"></div><br/>';
			
			
			
		echo '</div>'; // <div id="menumarg">
	}
	
}
?>