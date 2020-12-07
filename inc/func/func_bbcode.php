<?php
function bbCode($t)
// remplace les balises BBCode par des balises HTML
{
   // barre horizontale
   $t=str_replace("[/]", "<hr width=\"100%\" size=\"1\" />", $t);
   $t=str_replace("[hr]", "<hr width=\"100%\" size=\"1\" />", $t);
   
   $t = preg_replace('#\[color=(red|green|blue|yellow|purple|olive)\](.+)\[/color\]#isU', '<span style="color:$1">$2</span>', $t);
    
   // gras
   $t=str_replace("[b]", "<strong>", $t);
   $t=str_replace("[/b]", "</strong>", $t);
    
   // italique
   $t=str_replace("[i]", "<em>", $t);
   $t=str_replace("[/i]", "</em>", $t);
    
   // soulignement
   $t=str_replace("[u]", "<u>", $t);
   $t=str_replace("[/u]", "</u>", $t);
    
   // alignement centré
   $t=str_replace("[center]", "<div style=\"text-align: center\">", $t);
   $t=str_replace("[/center]", "</div>", $t);
    
   // alignement à droite
   $t=str_replace("[right]", "<div style=\"text-align: right\">", $t);
   $t=str_replace("[/right]", "</div>", $t);
    
   // alignement justifié
   $t=str_replace("[justify]", "<div style=\"text-align: justify\">", $t);
   $t=str_replace("[/justify]", "</div>", $t);
	
	// URL
	$t = preg_replace('#\[url=(.+)\](.+)#isU', '<a href="$1">$2',  $t);
	$t=str_replace("[/url]", "</a>", $t);
	
	// img
	$t=preg_replace('#\[img=(.+)\](.+)\[/img\]#isU', '<img src="$1" alt="$2">', $t);
	
	// Saut de lignes, tabulations et caractères
	$t=str_replace(array("\r\n", "\n", "\r"), '<br />', $t);
	
   return $t;
}
?>