<?php
    require_once("../QueryPDO.php");
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET") {
        if(is_null($idUser = QueryPDO::getInstance()->getIdByToken($_SESSION["token"]))) {
            return QueryPDO::getInstance()->ServiceReturnJson("4","Invalid Token");
        }
        else {
            $tabParams[':iduser'] = $idUser;
            $tabParams[':idfriend'] = $idUser;
            $requete = "SELECT COUNT(*) AS nombrePost, iduser
                        FROM post
                        WHERE iduser IN (SELECT idfriend FROM friend WHERE iduser = :iduser AND friend_accepted = 1)
                           OR iduser IN (SELECT iduser FROM friend WHERE idfriend = :idfriend AND friend_accepted = 1)
                        GROUP BY iduser
                        ORDER BY nombrePost DESC";
            $result = QueryPDO::getInstance()->query($requete, $tabParams);
            if ($result != null) {
                while ($ligne = $result->fetch(PDO::FETCH_ASSOC)) {
                    $lignes[] = $ligne;
                }
                echo (json_encode($lignes, JSON_PRETTY_PRINT));
                return QueryPDO::getInstance()->ServiceReturnJson("0","Ok");
            }
            else {
                return QueryPDO::getInstance()->ServiceReturnJson("3","No Data");
            }
        }
    }
    else {
        return QueryPDO::getInstance()->ServiceReturnJson("5","Wrong Request Method");
    }
?>