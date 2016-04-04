<?php 
require_once("../QueryPDO.php"); //Singleton connection bdd & communication + return en JSon

$_SESSION["token"]="BdyFvZsFsqE11uopcHFZ";
	if (isset($_SESSION['token'])) {
                if(is_null($IdUser = QueryPDO::getInstance()->getIdByToken($_SESSION["token"]))) {
                    return QueryPDO::getInstance()->ServiceReturnJson("4","Invalid Token");
                }

			//-------------------------------------------------------------------------
			//------------------------------  CODE ------------------------------------
			//------Il faut biensur changer les parametres en fonction du besoin-------
			
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
					    echo '<a href="http://localhost/devweb/projet/SocialNetworkServices/friend/listfriend.php?token='.$_SESSION["token"].'&page=' . $i . '">' . $i . '</a> '; // en dur
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
					$premierMessageAafficher = ($page - 1) * $nombre_de_msg_par_page/2;
					 
					// On ferme la requête avant d'en faire une autre
					


					//-------------------------------- Pour la pagination -----------------------------------------
					
					$reponse2 = QueryPDO::getInstance()->query('SELECT user.user_login FROM user AS U 
																INNER JOIN friend AS F ON U.iduser = F.iduser 
																INNER JOIN user ON user.iduser = F.idfriend 
																WHERE F.iduser= '.$IdUser.' AND F.friend_accepted = 1
																LIMIT ' . $premierMessageAafficher . ', ' . $nombre_de_msg_par_page/2);

					if(is_object($reponse2)){
						while($donnees = $reponse2->fetch()) 
							{	
								
							    echo ' amis avec :' . stripslashes(htmlspecialchars($donnees['user_login'])) . '</p>';
							}
					   
					}
						
					    $reponse3 = QueryPDO::getInstance()->query('SELECT user.user_login FROM user AS U 
																	INNER JOIN friend AS F ON U.iduser = F.idfriend 
																	INNER JOIN user ON user.iduser = F.iduser
																	WHERE F.idfriend='.$IdUser.' AND F.friend_accepted = 1 
																	LIMIT ' . $premierMessageAafficher . ', ' . $nombre_de_msg_par_page/2
																);
					if(is_object($reponse3)){	 
						while($donnees = $reponse3->fetch()) 
							{
								
							    echo '<p> amis avec :' . stripslashes(htmlspecialchars($donnees['user_login'])) . '</p>';
							}
					}
				
					//-----------------------------------------------------------------------------------------------
					//--------------------------------Pour la liste a renvoyer --------------------------------------
					$jsontab = array();
					$reponse2 = QueryPDO::getInstance()->query('SELECT user.user_login FROM user AS U 
																INNER JOIN friend AS F ON U.iduser = F.iduser 
																INNER JOIN user ON user.iduser = F.idfriend 
																WHERE F.iduser= '.$IdUser.' AND F.friend_accepted = 1
																');

					if(is_object($reponse2)){
						while($donnees = $reponse2->fetch()) 
							{	
								array_push($jsontab, $donnees['user_login']);
							  
							}
					   
					}
						
					    $reponse3 = QueryPDO::getInstance()->query('SELECT user.user_login FROM user AS U 
																	INNER JOIN friend AS F ON U.iduser = F.idfriend 
																	INNER JOIN user ON user.iduser = F.iduser
																	WHERE F.idfriend='.$IdUser.' AND F.friend_accepted = 1 
																	'
																);
					if(is_object($reponse3)){	 
						while($donnees = $reponse3->fetch()) 
							{
								array_push($jsontab, $donnees['user_login']);
							   
							}
					}
					//-----------------------------------------------------------------------------------------------
			//-------------------------------------------------------------------------
			//-------------------------------------------------------------------------

			if(is_null($reponse2 && $reponse3)){ //Si on fait un insert, on verifie que la requete a inseré une ligne, si ce n'est pas le cas la ligne etait déjà présente : code 7
				return QueryPDO::getInstance()->ServiceReturnJson("7","Nothing to update");
			}
			else{
				return QueryPDO::getInstance()->ServiceReturnJson("0",json_encode($jsontab)); //Code 0: tout s'est bien passé. Ici pas de retour donc description
			}
			
		}
  else {
        return QueryPDO::getInstance()->ServiceReturnJson("2","No token");
 }



?>