<?php
function connectSql(){
   try
	{
		$bdd = new PDO('mysql:host=localhost;dbname=delta;charset=utf8', 'user', 'nemollecommeungrospoisson123');
	}

	catch(Exception $e)
	{
			die('Erreur : '.$e->getMessage());
	}
	
   return $bdd;
}
?>