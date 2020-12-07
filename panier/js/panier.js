

// On créer un affichage d'image avec viewer.js qui permet d'afficher les images miniatures en grandes tailles de manière dinamyque.

var images = $('#viewer_init');

// on initialise viewer
images.viewer();

// On créer un affichage dinamyque de l'historique de commande ci dessous.

// On cache les classes hidden_aticles
$('.hidden_articles').toggle();

// On créer la fonction qui affiche les articles d'une commande.
$('a#articles_button').on('click', function(){

	var id = $(this).attr('class');

	$('#hidden_articles_'+id).toggle("fold", "slow");

});