<?php



		public function pagination($nombre_messages,$nombre_de_msg_par_page, $service, $token){
					 
					 
					 
					// on détermine le nombre de pages
					$nb_pages = ceil($nombre_messages / $nombre_de_msg_par_page);
					         
					 
					// Puis on fait une boucle pour écrire les liens vers chacune des pages
					echo 'Page : ';
					for ($i = 1 ; $i <= $nb_pages ; $i++)
					{
					    echo '<a href="http://localhost/devweb/projet/SocialNetworkServices/friend/listfriend.php?token='.$_GET["token"].'&page=' . $i . '">' . $i . '</a> '; // en dur
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
					
					$jsontab = array();
					$reponse2 = QueryPDO::getInstance()->query('SELECT user.user_login FROM user AS U 
																INNER JOIN friend AS F ON U.iduser = F.iduser 
																INNER JOIN user ON user.iduser = F.idfriend 
																WHERE F.iduser= '.$IdUser.' AND F.friend_accepted = 0
																LIMIT ' . $premierMessageAafficher . ', ' . $nombre_de_msg_par_page/2);

					if(is_object($reponse2)){
						while($donnees = $reponse2->fetch()) 
							{	
								array_push($jsontab, $donnees['user_login']);
							    echo 'est amis avec :' . stripslashes(htmlspecialchars($donnees['user_login'])) . '</p>';
							}
					   
					}
						
					    $reponse3 = QueryPDO::getInstance()->query('SELECT user.user_login FROM user AS U 
																	INNER JOIN friend AS F ON U.iduser = F.idfriend 
																	INNER JOIN user ON user.iduser = F.iduser
																	WHERE F.idfriend='.$IdUser.' AND F.friend_accepted = 0 
																	LIMIT ' . $premierMessageAafficher . ', ' . $nombre_de_msg_par_page/2
																);
					if(is_object($reponse3)){	 
						while($donnees = $reponse3->fetch()) 
							{
								array_push($jsontab, $donnees['user_login']);
							    echo '<p> est amis avec :' . stripslashes(htmlspecialchars($donnees['user_login'])) . '</p>';
							}
					}
				
}
?>