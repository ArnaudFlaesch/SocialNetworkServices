<?php 
	require_once("../QueryPDO.php"); //Singleton connection bdd & communication + return en JSon
	session_start();
	  // Verification de la methode de requete
	if (isset($_SESSION['token'])) {
		if(is_null($idUser = QueryPDO::getInstance()->getIdByToken($_SESSION["token"]))) {
			return QueryPDO::getInstance()->ServiceReturnJson("4","Invalid Token");
		}
		if(is_null($IdUser = QueryPDO::getInstance()->getIdByToken($_SESSION["token"]))){ //Code 4, en cas de parametre: "token" inconnu
			return QueryPDO::getInstance()->ServiceReturnJson("4","Invalid Token");
		}

		//-------------------------------------------------------------------------
		//------------------------------  CODE ------------------------------------
		//------Il faut bien sur changer les parametres en fonction du besoin-------


		$nombre_de_msg_par_page=10; // On met dans une variable le nombre de messages qu'on veut par page

		// On récupère le nombre total de messages

		$reponse=QueryPDO::getInstance()->query('SELECT COUNT(*) AS amitie FROM friend');
		$total_messages = $reponse->fetch();
		$nombre_messages=$total_messages['amitie'];



		// on détermine le nombre de pages
		$nb_pages = ceil($nombre_messages / $nombre_de_msg_par_page);


		// Puis on fait une boucle pour écrire les liens vers chacune des pages
		echo 'Page : ';
		for ($i = 1 ; $i <= $nb_pages ; $i++)
		{
			echo '<a href="http://localhost/devweb/projet/SocialNetworkServices/friend/finduser.php?token='.$_SESSION["token"].'&page=' . $i . '">' . $i . '</a> '; // en dur
		}
		echo "<br>";
		// Maintenant, on va afficher les messages
		// ---------------------------------------

		if (isset($_GET['page']))
		{
			$page = $_GET['page']; // On récupère le numéro de la page indiqué dans l'adresse
		}
		else // La variable n'existe pas, c'est la première fois qu'on charge la page
		{
			$page = 1; // On se met sur la page 1 (par défaut)
		}

		// On calcule le numéro du premier message qu'on prend pour le LIMIT de MySQL
		$premierMessageAafficher = ($page - 1) * $nombre_de_msg_par_page;

		// On ferme la requête avant d'en faire une autre

		$reponse1 = QueryPDO::getInstance()->query('SELECT user.user_name, user.user_firstname, user.user_token FROM user 
													WHERE user.iduser != '.$IdUser.'
													LIMIT ' . $premierMessageAafficher . ', ' . $nombre_de_msg_par_page);

		if(is_object($reponse1)){
			while($donnees1 = $reponse1->fetch()) {
				$reponse2 = QueryPDO::getInstance()->query('SELECT friend.friend_accepted FROM friend 
												WHERE ( iduser= '.$IdUser.' AND idfriend = '.QueryPDO::getInstance()->getIdByToken($donnees1['user_token']).') 
												OR 
												(iduser= '.QueryPDO::getInstance()->getIdByToken($donnees1['user_token']).' AND idfriend = '.$IdUser.')
												');

				if(is_object($reponse2)){

					while($donnees2 = $reponse2->fetch())
						{
							switch ($donnees2['friend_accepted']) {
									case -1:
										echo stripslashes(htmlspecialchars($donnees1['user_name'])) . '  -- '.stripslashes(htmlspecialchars($donnees1['user_firstname'])).' --- Declined</p>';
										break;
									case 0:
										echo stripslashes(htmlspecialchars($donnees1['user_name'])) . '  -- '.stripslashes(htmlspecialchars($donnees1['user_firstname'])).'--- Waiting for response</p>';
										break;
									case 1:
										echo stripslashes(htmlspecialchars($donnees1['user_name'])) . '  -- '.stripslashes(htmlspecialchars($donnees1['user_firstname'])).'--- Friend</p>';
										break;
								}

						}
				}
				else{
					 echo stripslashes(htmlspecialchars($donnees1['user_name'])) . '  -- '.stripslashes(htmlspecialchars($donnees1['user_firstname'])).'</p>';
				}

			}
		}
	}
	else {
		return QueryPDO::getInstance()->ServiceReturnJson("2","No token");
	}
?>