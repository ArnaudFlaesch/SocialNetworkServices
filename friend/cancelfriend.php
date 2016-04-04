<?php 
require_once("../QueryPDO.php"); //Singleton connection bdd & communication + return en JSon
  if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") { // Verification de la methode de requete
  	 if (isset($_SESSION['token'])) {
         if(is_null($idUser = QueryPDO::getInstance()->getIdByToken($_SESSION["token"]))) {
            return QueryPDO::getInstance()->ServiceReturnJson("4","Invalid Token");
         }
					if( isset($_POST["firstname"]) && isset($_POST["lastname"])){ // Verification de la présence des variables en paramètres

						
						if(is_null($IdUser = QueryPDO::getInstance()->getIdByToken($_SESSION["token"]))){ //Code 4, en cas de parametre: "token" inconnu
							return QueryPDO::getInstance()->ServiceReturnJson("4","Invalid Token");
						}

						//-------------------------------------------------------------------------
						//------------------------------  CODE ------------------------------------
						//------Il faut biensur changer les parametres en fonction du besoin-------

						$sql = "SELECT `iduser` FROM `user` WHERE `user_firstname`='".$_POST["firstname"]."' AND `user_name`='".$_POST["lastname"]."';	";
						$data = QueryPDO::getInstance()->query($sql);
						if(is_object($data))
							$data = $data->fetch();
						else
							return QueryPDO::getInstance()->ServiceReturnJson("7","Nothing to update");
						
						
						$sql="DELETE FROM `socialnetwork`.`friend` WHERE `friend`.`iduser` = ".$IdUser." AND `friend`.`idfriend` = ".$data["iduser"]." AND `friend`.`friend_accepted`= 0";
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

					return QueryPDO::getInstance()->ServiceReturnJson("1","Missing parameters"); //code 2: Parametres manquants
					}

				}
            else {
                return QueryPDO::getInstance()->ServiceReturnJson("2","No token");
            }
        }
    else {
        return QueryPDO::getInstance()->ServiceReturnJson("5","Wrong Request Method");
    }


?>