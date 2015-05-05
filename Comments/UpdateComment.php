<?php
    require_once("../QueryPDO.php");
    session_start();
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
            if (isset($_SESSION['token'])) {
                if(is_null($idUser = QueryPDO::getInstance()->getIdByToken($_SESSION["token"]))) {
                    return QueryPDO::getInstance()->ServiceReturnJson("4","Invalid Token");
                }
            else {
                if (isset($_POST['contentComment']) && isset($_POST['idComment'])) {
                    $tabParams[':contentComment'] = $_POST['contentComment'];
                    $tabParams[':idComment'] = $_POST['idComment'];
                    $requete = "UPDATE `socialnetwork`.`comment` SET `comment_content` = :contentComment WHERE `comment`.`idcomment` = :idComment;";
                    $result = QueryPDO::getInstance()->query($requete, $tabParams);
                    if($result != NULL){
                        if($result->rowCount()!= NULL)
                            return QueryPDO::getInstance()->ServiceReturnJson("0","Ok");                    
                    }
                    else
                         return QueryPDO::getInstance()->ServiceReturnJson("7","Nothing to update");
                    
                        
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