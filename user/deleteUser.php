<?php

	
session_start();
require_once("../QueryPDO.php");

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") { // Verification de la methode de requete
        
		if (isset($_POST['id'])) {// Verification que les $_POST[] existent
            
			include_once('token.php');
			
			$tabParams[':iduser'] = $_POST['id'];
			//Vérification qe l'utilisateur existe bien 
			$user = "SELECT * FROM user WHERE iduser = :iduser ";
			$data = QueryPDO::getInstance()->query($user);
			if(is_null($data)){
				return QueryPDO::getInstance()->ServiceReturnJson("2","User doesn't exist");
			} else {
				//Selection de la liste des notifications dans la table user_notification
				$notifUser = "SELECT * FROM  user_notification WHERE iduser = ".$idCurrent." ";
			
				$req = QueryPDO::getInstance()->query($notifUser);
				if(is_null($req)){
					return QueryPDO::getInstance()->ServiceReturnJson("7","Nothing to update");
				}

				else {
					while($dataNotifUser = $req->fetch(PDO::FETCH_ASSOC)){
					
					//Suppression des notifications
					$DelNotifUser = "DELETE FROM user_notification WHERE idnotification = ".$dataNotifUser['idnotification']." AND iduser = ".$idCurrent." ";
					$deleteNotifUser = QueryPDO::getInstance()->query($DelNotifUser);
					
					$DelNotif = "DELETE FROM notification WHERE idnotification = ".$dataNotifUser['idnotification']." ";	
					$deleteNotif = QueryPDO::getInstance()->query($DelNotif);
					
					}					
				}
				
				//Suppression des likes
				$delLike = "DELETE FROM `like` WHERE iduser = ".$idCurrent." ";
				$deleteLike = QueryPDO::getInstance()->query($delLike);
				
				//Suppression des amis
				$delFriend = "DELETE FROM `friend` WHERE iduser = ".$idCurrent." " ;
				$deleteFriend = QueryPDO::getInstance()->query($delFriend);
				
				//Suppression des Commentaires
				$delCom = "DELETE FROM `comment` WHERE iduser = ".$idCurrent." ";
				$deleteCom = QueryPDO::getInstance()->query($delCom);
				
				//Suppression des Postes
				$delPost = "DELETE FROM `post` WHERE iduser = ".$idCurrent." " ;
				$deletePost = QueryPDO::getInstance()->query($delPost);
				
				//Suppression des tags
				$delTage = "DELETE FROM `tag` WHERE iduser = ".$idCurrent." " ;
				$deleteTag = QueryPDO::getInstance()->query($delTage);
				
				$del = "DELETE FROM user WHERE iduser = ".$idCurrent." ";
				$delUser = QueryPDO::getInstance()->query($del);
				
				return QueryPDO::getInstance()->ServiceReturnJson("0","ok");
			}		
		}        
		else {	
			return QueryPDO::getInstance()->ServiceReturnJson("1","Missing parameters");
		}
		
		} else {
	
        return QueryPDO::getInstance()->ServiceReturnJson("5","Wrong Request Method");
		session_destroy();
		
	}


?>