
function showForum(id){
	$('div.'+id).toggle('fold', 900);
}

$('a#forum_mini_tableau').click(function(){
	
	var text = $('a#forum_mini_tableau').text();

	if(text == '↖ Fermer toutes les catégories ↗'){
		$('a#forum_mini_tableau').html('&#8601; Ouvrir toutes les catégories &#8600;');
	}else{
		$('a#forum_mini_tableau').html('&#8598; Fermer toutes les catégories &#8599;');
	}

	// On active le bouton qui ouvre toutes les catégories
	$('div#catforum').toggle('fold', 300);
});