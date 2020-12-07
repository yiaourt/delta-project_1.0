

function chargeArticle(id) {

	$('#shop').hide("fade", 300);
	$('#sticky_block').hide("fade", 300);
	$('#category').hide("fade", 300);

	// On remonte un peu le bloc #article
	$('#article').css('margin-top', '-75px');

	$('#article').load('shop/ajax/aff_article.php', {
		id : id
	});

	$('#article').show("fade", 300);
}

function dechargeArticle() {

	$('#article').hide("fade", 300);

	$('#shop').show("fade", 300);
	$('#sticky_block').show("fade", 300);
	$('#category').show("fade", 300);

	// On redescend le bloc #article
	$('#article').css('margin-top', '0');
}

function listCategory(id) {
	$('#shop').hide().load('shop/ajax/list_by_category.php', {
		id : id
	}).fadeIn('500');
}

function chargeImage1(id) {

    $('#conteneur_img_article').load('shop/ajax/img.php', {
		id : id,
		image : "image1"
	});
}

function chargeImage2(id) {

    $('#conteneur_img_article').load('shop/ajax/img.php', {
		id : id,
		image : "image2"
	});
}
function chargeImage3(id) {

    $('#conteneur_img_article').load('shop/ajax/img.php', {
		id : id,
		image : "image3"
	});
}
function chargeImage4(id) {

    $('#conteneur_img_article').load('shop/ajax/img.php', {
		id : id,
		image : "image4"
	});
}
function chargeImage5(id) {

    $('#conteneur_img_article').load('shop/ajax/img.php', {
		id : id,
		image : "image5"
	});
}

function successNoty(titre){
	// On Utilise le plugin Noty.js pour faire un système de notifications.
	new Noty({

		type: 'success',
   		layout: 'bottomLeft',

	    theme: 'bootstrap-v4',

	    text: '+1 '+titre,

	    timeout: 1000,
	    progressBar: true,

	    animation: {
	        open: 'animated bounceInRight', // Animate.css class names
	        close: 'animated bounceOutUp' // Animate.css class names
	    }

	}).show();
}

function errorNoty(){
	// On Utilise le plugin Noty.js pour faire un système de notifications.
	new Noty({

		type: 'error',
   		layout: 'bottomLeft',

	    theme: 'bootstrap-v4',

	    text: 'Erreur: 15 Articles maximum.',
	    timeout: 1000,
	    progressBar: true,

	    animation: {
	        open: 'animated bounceInRight', // Animate.css class names
	        close: 'animated bounceOutUp' // Animate.css class names
	    }

	}).show();
}

function addPanier(id, titre) {

	var total_panier = $('#total_panier').html();

	if(!total_panier){
		var total_panier = $('#total_hidden_panier').html();
	}

	console.log(total_panier);

	if(total_panier >= 15){ // Si le total du panier est plus grand ou égal à 15
		
		errorNoty(); // On retourne une notification.
	
	}else{

		// On ajoute l'article au panier.
	 	$(document).load('shop/ajax/add_panier.php', {
			id : id
		});
	 	// et on retourne une notification
		successNoty(titre);
	}
}
	
// On créer un évenement sur la touche échape qui permet de revenir au shop.
$(document).on('keydown', function(event) {
   	if (event.key == "Escape") {
   		
        $('#article').hide("fade", 300);

		$('#shop').show("fade", 300);
		$('#sticky_block').show("fade", 300);
		$('#category').show("fade", 300);

		// On redescend le bloc #article
		$('#article').css('margin-top', '0');
   }
});

function articlePrec(id){

	var id = id-1;

	$('#shop').hide("fade", 300);
	$('#sticky_block').hide("fade", 300);
	$('#category').hide("fade", 300);

	// On remonte un peu le bloc #article
	$('#article').css('margin-top', '-75px');

	$('#article').load('shop/ajax/aff_article.php', {
		id : id,
		direction : 'moins'
	});

	$('#article').show("fade", 300);
}

function articleSuiv(id){

	var id = id+1;

	$('#shop').hide("fade", 300);
	$('#sticky_block').hide("fade", 300);
	$('#category').hide("fade", 300);

	// On remonte un peu le bloc #article
	$('#article').css('margin-top', '-75px');

	$('#article').load('shop/ajax/aff_article.php', {
		id : id,
		direction : 'plus'
	});

	$('#article').show("fade", 300);
}

$(document).on("keydown", function(event) {
   	if (event.key == "ArrowLeft") {
		
		if(typeof id == 'number'){
   			articlePrec(id);
   		}

   	}
});

$(document).on("keydown", function(event) {
   	if (event.key == "ArrowRight") {
   		
   		if(typeof id == 'number'){
   			articleSuiv(id);
   		}

   	}
});

// Les 2 prochaines fonctions ci dessous, concerne les boutons list et unlist du shop.
function listView(){

	$('#shop').css('flex-direction', 'column').load('shop/ajax/view_list.php');
}

function unlistView(){
	$('#shop').css('flex-direction', 'row').load('shop/ajax/view_category.php');
}