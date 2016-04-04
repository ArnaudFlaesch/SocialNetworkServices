<?php
    require_once("../QueryPDO.php");
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET") {
        if (isset($_SESSION['token'])) {
            if(is_null($idUser = QueryPDO::getInstance()->getIdByToken($_SESSION["token"]))) {
                return QueryPDO::getInstance()->ServiceReturnJson("4","Invalid Token");
            }
            else {
                if (isset($_GET['limit'])) {
                    $tabParams[':limit'] = $_GET['limit'];
                }
                else {
                    $tabParams[':limit'] = 10;
                }
                if (isset($_GET['offset'])) {
                    $tabParams[':offset'] = $_GET['offset'];
                }
                else {
                    $tabParams[':offset'] = 1;
                }
                $tabParams[':iduser'] = $idUser;
                $tabParams[':idfriend'] = $idUser;
                $requete = "SELECT COUNT(*) AS nombrePost, iduser
                            FROM post
                            WHERE iduser IN (SELECT idfriend FROM friend WHERE iduser = :iduser AND friend_accepted = 1)
                               OR iduser IN (SELECT iduser FROM friend WHERE idfriend = :idfriend AND friend_accepted = 1)
                            GROUP BY iduser
                            ORDER BY nombrePost DESC
                            LIMIT :limit OFFSET :offset";
                $result = QueryPDO::getInstance()->query($requete, $tabParams);
                if ($result != null) {
                    $lignes[] = QueryPDO::getInstance()->ServiceReturnJson("0","Ok");
                    while ($ligne = $result->fetch(PDO::FETCH_ASSOC)) {
                        $lignes[] = $ligne;
                    }
                    echo (json_encode($lignes, JSON_PRETTY_PRINT));
                }
                else {
                    return QueryPDO::getInstance()->ServiceReturnJson("3","No Data");
                }
            }
        }
        else {
            return QueryPDO::getInstance()->ServiceReturnJson("2","No token");
        }
    }
    else {
        return QueryPDO::getInstance()->ServiceReturnJson("5","Wrong Request Method");
    }
?>