<?php

session_start();
require_once("../QueryPDO.php");

    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
		if (isset($_POST['idpost']) && isset($_SESSION['token'])){
			//Recherche du like en fonction du token sur suppression//
			$tabParams[':token'] = $_SESSION['token'];
			$user = "SELECT * FROM user WHERE user_token = :token";
			$Data = QueryPDO::getInstance()->query($user, $tabParams);
			
			$dataUser = $Data->fetch(PDO::FETCH_ASSOC);

			$tabParams[':idpost'] = $_POST['idpost'];
			$req = "DELETE FROM `like` WHERE idpost = :idpost";
				
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