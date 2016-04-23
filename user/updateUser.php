<?php
session_start();
require_once("../QueryPDO.php");
	if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
		//Récupération du token puis selection de l'utilisateur
		if(isset($_SESSION['token'])){
		$req = "SELECT * FROM user WHERE user_token = '".$_SESSION['token']."'";
		$dataCurrentUser = QueryPDO::getInstance()->query($req);
			
		$array = $dataCurrentUser->fetch(PDO::FETCH_ASSOC);
		
		
			//Recherche des champs à modifier
			if(isset($_POST['user_login'])){
			
				$userLogin = $_POST['user_login'];
				
			} else {
				$userLogin = $array['user_login'];
			
			}
			
			if(isset($_POST['user_password'])){
			
				$userPassword = $_POST['user_password'];
				
			} else {
				$userPassword = $array['user_password'];
			
			}
			
			if(isset($_POST['user_name'])){
			
				$userName = $_POST['user_name'];
				
			} else {
				$userName = $array['user_name'];
			
			}
			
			if(isset($_POST['user_firstname'])){
			
				$userFirstname = $_POST['user_firstname'];
				
			} else {
				$userFirstname = $array['user_firstname'];
			
			}
			
			if(isset($_POST['user_birthdate'])){
			
				$userDate = $_POST['user_birthdate'];
				
			} else {
				$userDate = $array['user_birthdate'];
			
			}
			
			if(isset($_POST['user_mail'])){
			
				$userMail = $_POST['user_mail'];
				
			} else {
				$userMail = $array['user_mail'];
			
			}
			
		
		//Mise à jour
		$update = "UPDATE user SET user_login = '".$userLogin."' , user_password = '".$userPassword."' , 
					user_name = '".$userName."' , user_firstname = '".$userFirstname."' , user_birthdate = ".$userDate ." , user_mail = '".$userMail."' WHERE user_token = '".$_SESSION['token']."'";
		$execUpdate = QueryPDO::getInstance()->query($update);
		echo (json_encode($execUpdate, JSON_PRETTY_PRINT));
		return QueryPDO::getInstance()->ServiceReturnJson("0","ok");
		}
		else {
		
			return QueryPDO::getInstance()->ServiceReturnJson("4","Invalid token");
			
		}
			
	}	
	else {
	
		return QueryPDO::getInstance()->ServiceReturnJson("5","Wrong Request Method");
		
	}

?>