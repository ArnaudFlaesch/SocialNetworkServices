<?php 
require_once("QueryPDO.php"); //Singleton connection bdd & communication + return en JSon

  if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") { // Verification de la methode de requete

		if(isset($_POST["token"]) && isset($_POST["firstname"]) && isset($_POST["lastname"])){ // Verification de la présence des variables en paramètres

			
			if(is_null($IdUser = QueryPDO::getInstance()->getIdByToken($_POST["token"]))){ //Code 4, en cas de parametre: "token" inconnu
				return QueryPDO::getInstance()->ServiceReturnJson("4","Invalid Token");
			}

			//-------------------------------------------------------------------------
			//------------------------------  CODE ------------------------------------
			//------Il faut biensur changer les parametres en fonction du besoin-------
			$sql = "SELECT `iduser` FROM `user` WHERE `user_firstname`='".$_POST["firstname"]."' AND `user_name`='".$_POST["lastname"]."';	";
			$data = QueryPDO::getInstance()->query($sql);
			$data = $data->fetch();
			

			$sql="INSERT INTO `socialnetwork`.`friend` (`iduser`, `idfriend`, `friend_accepted`) VALUES ('".$IdUser."', '".$data["iduser"]."', '0');";
			$insert = QueryPDO::getInstance()->query($sql);
		
			//-------------------------------------------------------------------------
			//-------------------------------------------------------------------------

			if(is_null($insert)){ //Si on fait un insert, on verifie que la requete a inseré une ligne, si ce n'est pas le cas la ligne etait déjà présente : code 7
				return QueryPDO::getInstance()->ServiceReturnJson("7","Nothing to update");
			}
			else{
				return QueryPDO::getInstance()->ServiceReturnJson("0","Ok"); //Code 0: tout s'est bien passé. Ici pas de retour donc description
			}
			
		}
		else{

		return QueryPDO::getInstance()->ServiceReturnJson("2","Missing parameters"); //code 2: Parametres manquants
		}

	}
	else{

		return QueryPDO::getInstance()->ServiceReturnJson("1","Wrong Request Methode"); // code 1: mauvaise methode de requete
	}


?>