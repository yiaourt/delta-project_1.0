
// Ci dessous un script qui connecte dynamiquement un utilisateur dans la base de données
// On charge connect.php qui met à 1 le tableau 'is_connect' de la table user seulement si la variable php SESSION existe
$(document).load('irc/user/connect.php');

$(window).on('beforeunload', function (){ // lorsque la fenêtre envois l'évenement de se fermer
	
	$(document).load('irc/user/disconnect.php'); // On charge la page php disconnect

	return true;
});