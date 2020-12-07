// On scroll sur la conversation dans le html body.
$('html, body').animate({
        scrollTop: $("article").offset().top
    }, 50);

// On fait un évenement de click sur le bouton envoyer.
$('#envoyer').click(function(e){
    e.preventDefault(); // on empêche le bouton d'envoyer le formulaire

    var message = encodeURIComponent( $('#message').val() ); // On sécurise les données

    if(message != ""){ // on vérifie que les variables ne sont pas vides
        
        $.ajax({ // On fait la requête ajax
            url : "irc/send.php", // on donne l'URL du fichier de traitement
            type : "POST", // la requête est de type POST
            data : "message=" + message // et on envoie nos données
        });

        $('#message').val('');
        charger();
    }
});

function charger(){ 

    setTimeout( function(){
        
        // On charge les utilisateurs en ligne sur le tchat.
        $('#useronline').load('irc/refresh_user.php');
        // On charge la conversation dans le bloc div id "conversation"
        $('#conversation').load('irc/refresh.php');
        // On scroll jusqu'au dernier message.
        $('#conversation').scrollTop(99999);

        charger(); // On boucle la fonction charger()

    }, 2000);

}

// On charge les utilisateurs en ligne sur le tchat.
$('#useronline').load('irc/refresh_user.php');
// On charge la conversation dans le bloc div id "conversation"
$('#conversation').load('irc/refresh.php');
// On scroll jusqu'au dernier message.
$('#conversation').scrollTop(99999);

charger(); // Et on boucle cela, toutes les 2 secondes.
