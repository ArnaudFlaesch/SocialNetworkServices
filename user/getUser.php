<?php
session_start();
require_once("../QueryPDO.php");

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET") { // Verification de la methode de requete
        
		if (isset($_GET['loginUser']) && isset($_GET['passwordUser'])) {// Verification que les $_GET[] existent
            
			include_once('token.php');
			
			//Ajout des $_GET dans un Array + bind
			$tabParams[':login'] = $_GET['loginUser'];
			$tabParams[':password'] = $_GET['passwordUser'];
			$token = getToken(20);

			//Requete qui selectionne la combinaison valide de "loginUser" / "passwordUser"
			$requete = "SELECT *      FROM user 
									  WHERE user_login = :login
									  AND	user_password = :password";
									  
			$data = QueryPDO::getInstance()->query($requete, $tabParams);
			
			//Si la requete s'exécute bien										 
            if (is_null($data)) {
			
                 return QueryPDO::getInstance()->ServiceReturnJson("2","User doesn't exist"); //Aucun user n existe avec ces identifiants
				 
                }
				else {
					//Génération du nouveau token
					$tabParams[':token'] = getToken(20); // fonction de génération du token
					$tabParams[':login'] = $_GET['loginUser'];
					$tabParams[':password'] = $_GET['passwordUser'];
					
					
					//Mise a jour du token dans la BDD
					$updateToken = "UPDATE user set user_token = :token
											 WHERE user_login = :login 
											 AND	user_password = :password ";
											 
					$update = QueryPDO::getInstance()->query($updateToken, $tabParams);
					$_SESSION['token'] = $token;
					
					return QueryPDO::getInstance()->ServiceReturnJson("0","$token"); //Aucun user n existe avec ces identifiants
											 
				}
				
			//Transformation en json
            echo (json_encode($update, JSON_PRETTY_PRINT));
            unset($requete);
            unset($bdd);
        }
		else 
		{	
			return QueryPDO::getInstance()->ServiceReturnJson("1","Missing parameters");
		}
    }
	else {
	
        return QueryPDO::getInstance()->ServiceReturnJson("5","Wrong Request Method");
		
		
	}

?>