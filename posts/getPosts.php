<?php
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET") {
        if (isset($_SESSION['user_token'])) {
            $tabParams[':token'] = $_SESSION['user_token'];
            $bdd = new PDO('mysql:host=localhost;dbname=socialnetwork','root','');
            $requete = $bdd->prepare("SELECT *
                                      FROM post P
                                      WHERE iduser IN (SELECT idfriend FROM friend WHERE iduser = :iduser AND friend_accepted = 1)
                                      OR iduser IN (SELECT iduser FROM friend WHERE idfriend = :idfriend AND friend_accepted = 1)
                                      OR iduser = :idUser
                                      ORDER BY post_date DESC");
            if ($requete && $requete->execute($tabParams)) {
                $codeRetour = 0;
                while ($ligne = $getRequetes->fetch(PDO::FETCH_ASSOC)) {
                    $result[] = $ligne;
                }
            }
            echo (json_encode($result, JSON_PRETTY_PRINT));
            unset($requete);
            unset($bdd);
        }
    }
?>