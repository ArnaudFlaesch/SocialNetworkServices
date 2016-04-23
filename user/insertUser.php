<?php
session_start();
require_once("../QueryPDO.php"); //Singleton connection bdd & communication + return en JSon
include_once('token.php');
	if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {// Verification de la methode de requete
        $bdd = new PDO('mysql:host=localhost;dbname=socialnetwork','root',''); // Connexion à la bdd
		
		//Si tous les champs existent
        if (isset($_POST['loginUser']) && isset($_POST['passwordUser']) && 
		isset($_POST['nameUser']) && isset($_POST['firstnameUser']) && 
		isset($_POST['birthdateUser']) && isset($_POST['mailUser'])) {
         			
			$tok = getToken(20); // fonction de génération du token
			$insert=null;
			
			if(is_null($verification = QueryPDO::getInstance()->query("SELECT * FROM `user` WHERE `user_login`= '".$_POST['loginUser']."'  AND `user_password`= '".$_POST['passwordUser']."'"))){
			
				//Requete d'insertion user dans la bdd
				$requete = "INSERT INTO `user`(`iduser`, `user_login`, `user_password`, `user_name`, `user_firstname`, `user_birthdate`, `user_mail`, `user_token`) 
				VALUES ('','".$_POST['loginUser']."','".$_POST['passwordUser']."','".$_POST['nameUser']."','".$_POST['firstnameUser']."',".$_POST['birthdateUser'].",'".$_POST['mailUser']."','$tok')";
					
				$insert = QueryPDO::getInstance()->query($requete);
				
			} else {
				
				return QueryPDO::getInstance()->ServiceReturnJson("3","User already exist"); 
			}
			
				
			if(is_null($insert)){ //Si on fait un insert, on verifie que la requete a inseré une ligne, si ce n'est pas le cas la ligne etait déjà présente : code 7
				
				return QueryPDO::getInstance()->ServiceReturnJson("7","Nothing to update"); 
			}
			else {
				//Création variable de session token
				$_SESSION['token'] = $tok ;
				return QueryPDO::getInstance()->ServiceReturnJson("0","$tok"); 
			}
		}
         else {				
		 
				return QueryPDO::getInstance()->ServiceReturnJson("1","Missing parameters"); 	
		}
    }     
	else{
		
		return QueryPDO::getInstance()->ServiceReturnJson("5","Wrong Request Methode"); 
	}
	
	    echo (json_encode($insert , JSON_PRETTY_PRINT));
	 unset($requete);
     unset($bdd);

?>