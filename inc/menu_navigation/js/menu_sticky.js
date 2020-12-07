$(document).ready(function() {

	// On déclare les variables des menu cacher
	var logo = $('#logodelta_theme');

	var titre = $('#titre_delta_theme');

	var home_icone = $('#home_icone');

	var forum_icone = $('#forum_icone');

	var shop_icone = $('#shop_icone');

	var irc_icone = $('#irc_icone');

	var connexion_sticky = $('#connexion_sticky');

	var profil_deroulant_sticky = $('#profil_deroulant_sticky');


	$('#nav_barre_top').sticky({ // On rend le menu sticky avec sticky plugin

			center:true,
			zIndex:2
	});

	$('#nav_barre_top').on('sticky-start', function() { 

		logo.css("visibility", "visible");
		titre.html('Delta-Project').css("visibility", "visible");
		home_icone.css("visibility", "visible");
		forum_icone.css("visibility", "visible");
		shop_icone.css("visibility", "visible");
		irc_icone.css("visibility", "visible");
		connexion_sticky.css("visibility", "visible");

		$('.logo').removeClass(['animated', 'fadeInLeftBig']).addClass(['animated', 'fadeOutLeftBig']);
		$('#connected').removeClass(['animated', 'fadeInDown']).addClass(['animated', 'fadeOutUp']);
		$('#noconnect').removeClass(['animated', 'fadeInUp']).addClass(['animated', 'fadeOutUp']);

	});

	$('#nav_barre_top').on('sticky-end', function() { 

		logo.css("visibility", "hidden");
		titre.html('thèmes :').css("visibility", "visible");
		home_icone.css("visibility", "hidden");
		forum_icone.css("visibility", "hidden");
		shop_icone.css("visibility", "hidden");
		irc_icone.css("visibility", "hidden");
		connexion_sticky.css("visibility", "hidden");

		profil_deroulant_sticky.css('visibility', 'hidden');

		$('.logo').removeClass(['animated', 'fadeOutLeftBig']).addClass(['animated', 'fadeInLeftBig']);
		$('#connected').removeClass(['animated', 'fadeOutUp']).addClass(['animated', 'fadeInDown']);
		$('#noconnect').removeClass(['animated', 'fadeOutUp']).addClass(['animated', 'fadeInUp']);

	});

	// On créer également une fonction qui affichera le menu du profil

	function afficheUser(){

		profil_deroulant_sticky.css('visibility', 'visible');

	}

	function cacheUser(){

		profil_deroulant_sticky.css('visibility', 'hidden');
	}


	// On fait la fonction qui affiche le menu sur mobile
	$('#menu_mobile').on('click', function(){

		$('#liste_mobile').toggle();
	});

});
