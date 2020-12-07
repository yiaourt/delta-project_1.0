$('#savebutton').button(); // Le bouton enregistrer en jqueryUI

// Ci dessous concerne les image à afficher dans la modification d'un article du shop.

$('#mod_img_article').hide(); // On cache le formulaire de modification

function chargeImage(id) { // La fonction qui affiche le formulaire depuis le lien <a> de l'image de l'article.

	$('#img_article').hide();

	$('#conteneur_icone_img_article').hide();

	$('#mod_img_article').show();
}

function dechargeImage(id) { // La fonction qui affiche le formulaire depuis le lien <a> de l'image de l'article.

	$('#img_article').show();

	$('#conteneur_icone_img_article').show();

	$('#mod_img_article').hide();
}

// Ci dessous concerne les catégories pouvant être drag and drop.

$('span#admin_shop_category').draggable({ // On rend les category draggable
    containment : '#category_field'
});

$('#category_form').hide(); // On cache le formulaire qui ajoute les catégories (à afficher en cas d'aide.)

function initialize_varID(id) {
	var id = id;
	
	$('#admin_drop_category').droppable({
		accept: '#admin_shop_category',

		drop: function(){

			alert('Catégorie ajouter !');

			var value = $('#category_form').val();

			if(value == ''){
				$('#category_form').html(id);
			}else{
				$('#category_form').html(value+', '+id);
			}

			var searchID_1 = value.indexOf(id);
			var searchID_2 = value.indexOf(', '+id);

			if(searchID_1 == -1){

			}else{
				if(searchID_2 == -1){
					$('#category_form').each(function() {
			    		var text = $(this).text();

			    		text = text.replace(', '+id, '');

					    $(this).text(text);

					});
				}else{
					$('#category_form').each(function() {
			    		var text = $(this).text();

					    text = text.replace(', '+id, '');

					    $(this).text(text);

					});
				}
			}
		}
	});

	$('#admin_undrop_category').droppable({
		accept: '#admin_shop_category',

		drop: function(){

			alert('Catégorie retirer !');

			$('#category_form').each(function() {
			    var text = $(this).text();

			    text = text.replace(', '+id, '');
			    text = text.replace(id+', ', '');
			    text = text.replace(id, '');

			    $(this).text(text);
			});
		}
	});
	
}