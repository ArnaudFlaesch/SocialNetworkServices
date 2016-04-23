<?php

session_start();
require_once("../QueryPDO.php");

    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
		if (isset($_POST['like_date']) && isset($_POST['idcomment'])){
			//Ajout d'un like d'un commentaire en fonction du token et de l idcomment//
			$query = "SELECT * FROM user WHERE user_token = :token";
			$tabParams[':token'] = $_SESSION['token'];
			$Data = QueryPDO::getInstance()->query($query, $tabParams);
			
			$dataUser = $Data->fetch(PDO::FETCH_ASSOC);
			$tabParams[':like_date'] = $_POST['like_date'];
			$tabParams[':iduser'] = $dataUser['iduser'];
			$tabParams[':idcomment'] = $_POST['idcomment'];
			$req = "INSERT INTO `like`(`idlike`, `like_date`, `iduser`, `idcomment`) VALUES ('',:like_date, :iduser, :idcomment)";
				
			$exec = QueryPDO::getInstance()->query($req, $tabParams);
			echo (json_encode($exec, JSON_PRETTY_PRINT));
			return QueryPDO::getInstance()->ServiceReturnJson("0","ok");
		}
	else {
		
			return QueryPDO::getInstance()->ServiceReturnJson("1","Missing parameters");
		}

	}
	else {
	
		return QueryPDO::getInstance()->ServiceReturnJson("5","Wrong Request Method");
		
	}
?>