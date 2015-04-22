<?php 
require_once("QueryPDO.php"); //Singleton connection bdd & communication + return en JSon

  if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET") { // Verification de la methode de requete

		if(isset($_GET["token"])){ // Verification de la présence des variables en paramètres

			
			if(is_null($IdUser = QueryPDO::getInstance()->getIdByToken($_GET["token"]))){ //Code 4, en cas de parametre: "token" inconnu
				return QueryPDO::getInstance()->ServiceReturnJson("4","Invalid Token");
			}

			//-------------------------------------------------------------------------
			//------------------------------  CODE ------------------------------------
			//------Il faut biensur changer les parametres en fonction du besoin-------



			
					// Connexion à la base de données
				
					   
					 
					 
					// Si tout va bien, on peut continuer
					 
					$nombre_de_msg_par_page=5; // On met dans une variable le nombre de messages qu'on veut par page
					 
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
					    echo '<a href="http://localhost/devweb/projet/SocialNetworkServices/friend/listfriend.php?token='.$_GET["token"].'&page=' . $i . '">' . $i . '</a> ';
					}
 
					// Maintenant, on va afficher les messages
					// ---------------------------------------
					 
					if (isset($_GET['page']))
					{
					    $page = $_GET['page']; // On récupère le numéro de la page indiqué dans l'adresse (livredor.php?page=4)
					}
					else // La variable n'existe pas, c'est la première fois qu'on charge la page
					{
					    $page = 1; // On se met sur la page 1 (par défaut)
					}
					 
					// On calcule le numéro du premier message qu'on prend pour le LIMIT de MySQL
					$premierMessageAafficher = ($page - 1) * $nombre_de_msg_par_page;
					 
					// On ferme la requête avant d'en faire une autre
					
					$reponse->closeCursor();
					$reponse = QueryPDO::getInstance()->query('SELECT * FROM friend ORDER BY iduser DESC LIMIT ' . $premierMessageAafficher . ', ' . $nombre_de_msg_par_page);
					 
					while($donnees = $reponse->fetch()) 
					{
						$jsontab[$donnees['iduser']]=$donnees['idfriend'];
					    echo '<p><strong>' . stripslashes(htmlspecialchars($donnees['iduser'])) . '</strong> est amis avec :' . stripslashes(htmlspecialchars($donnees['idfriend'])) . '</p>';
					}
					 
					
					


			//-------------------------------------------------------------------------
			//-------------------------------------------------------------------------

			if(is_null($reponse)){ //Si on fait un insert, on verifie que la requete a inseré une ligne, si ce n'est pas le cas la ligne etait déjà présente : code 7
				return QueryPDO::getInstance()->ServiceReturnJson("7","Nothing to update");
			}
			else{
				return QueryPDO::getInstance()->ServiceReturnJson("0",json_encode($jsontab)); //Code 0: tout s'est bien passé. Ici pas de retour donc description
			}
			
		}
		else{

			return QueryPDO::getInstance()->ServiceReturnJson("1","Missing parameters"); //code 2: Parametres manquants
		}

	}
	else{

		return QueryPDO::getInstance()->ServiceReturnJson("5","Wrong Request Methode"); // code 1: mauvaise methode de requete
	}


?>