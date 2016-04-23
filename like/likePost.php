<?php

session_start();
require_once("../QueryPDO.php");

    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
		if (isset($_POST['like_date']) && isset($_POST['idpost'])){
			//Ajout d'un like d'un post en fonction du token et de l idpost//
			$tabParams[':token'] = $_SESSION['token'];
			$user = "SELECT * FROM user WHERE user_token = :token";
			$Data = QueryPDO::getInstance()->query($user, $tabParams);
			
			$dataUser = $Data->fetch(PDO::FETCH_ASSOC);


			$tabParams[':like_date'] = $_POST['like_date'];
			$tabParams[':iduser'] = $dataUser['iduser'];
			$tabParams[':idpost'] = $_POST['idpost'];
			$req = "INSERT INTO `like`(`idlike`, `like_date`, `iduser`, `idpost`) VALUES ('', :like_date, :iduser, :idpost)";
				
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