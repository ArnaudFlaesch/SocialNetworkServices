<?php
require_once("../QueryPDO.php");
    session_start();
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET") {
        if (isset($_SESSION['token'])) {
            if(is_null($idUser = QueryPDO::getInstance()->getIdByToken($_SESSION["token"]))) {
                return QueryPDO::getInstance()->ServiceReturnJson("4","Invalid Token");
            }
            else {
                if (isset($_GET['idpost'])) {
                    $tabParams[':idpost'] = $_GET['idpost'];
                    $requete = "DELETE FROM user_notification WHERE idnotification IN
                                    (SELECT idNotification FROM notification WHERE idcomment IN
                                        (SELECT idcomment FROM comment WHERE idpost = :idpost) )";
                    QueryPDO::getInstance()->query($requete, $tabParams);
                    $requete = "DELETE FROM user_notification WHERE idnotification IN
                                    (SELECT idNotification FROM notification WHERE idtag IN
                                        (SELECT idtag FROM tag WHERE idpost = :idpost) )";
                    QueryPDO::getInstance()->query($requete, $tabParams);
                    $requete = "DELETE FROM notification WHERE idtag IN
                                        (SELECT idtag FROM tag WHERE idpost = :idpost)";
                    QueryPDO::getInstance()->query($requete, $tabParams);
                    $requete = "DELETE FROM tag WHERE idpost = :idpost";
                    QueryPDO::getInstance()->query($requete, $tabParams);
                    $requete = "DELETE FROM socialnetwork.like WHERE idcomment IN (SELECT idcomment FROM comment WHERE idpost = :idpost)";
                    QueryPDO::getInstance()->query($requete, $tabParams);
                    $requete = "DELETE FROM comment WHERE idpost = :idpost";
                    QueryPDO::getInstance()->query($requete, $tabParams);
                    $requete = "DELETE FROM socialnetwork.like WHERE idpost = :idpost";
                    QueryPDO::getInstance()->query($requete, $tabParams);
                    $requete = "DELETE FROM post WHERE idpost = :idpost";
                    QueryPDO::getInstance()->query($requete, $tabParams);
                    return QueryPDO::getInstance()->ServiceReturnJson("0","Ok");
                }
                else {
                    return QueryPDO::getInstance()->ServiceReturnJson("1","Missing parameters");
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