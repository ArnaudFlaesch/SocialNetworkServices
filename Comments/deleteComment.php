<?php
    require_once("../QueryPDO.php");
    session_start();
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
            if (isset($_SESSION['token'])) {
                if(is_null($idUser = QueryPDO::getInstance()->getIdByToken($_SESSION["token"]))) {
                    return QueryPDO::getInstance()->ServiceReturnJson("4","Invalid Token");
                }
                    else {
                        if (isset($_POST['idComment'])) {
                            $tabParams[':idComment'] = $_POST['idComment'];

                            $requete = "SELECT * FROM `socialnetwork`.`comment` WHERE `comment`.`idcomment` = :idComment;";
                            $result = QueryPDO::getInstance()->query($requete, $tabParams);
                            if($result!= NULL){

                                $requete = "DELETE FROM `socialnetwork`.`user_notification` WHERE `user_notification`.`idnotification` in (
                                    SELECT `idnotification` FROM `socialnetwork`.`notification` WHERE `notification`.`idcomment` = :idComment);";
                                QueryPDO::getInstance()->query($requete, $tabParams);

                                $requete = "DELETE FROM `socialnetwork`.`notification` WHERE `notification`.`idcomment` = :idComment;";
                                QueryPDO::getInstance()->query($requete, $tabParams);

                                $requete = "DELETE FROM `socialnetwork`.`tag` WHERE `tag`.`idcomment` = :idComment;";
                                QueryPDO::getInstance()->query($requete, $tabParams);

                                $requete = "DELETE FROM `socialnetwork`.`like` WHERE `like`.`idcomment` = :idComment;";
                                QueryPDO::getInstance()->query($requete, $tabParams);

                                $requete = "DELETE FROM `socialnetwork`.`comment` WHERE `comment`.`idcomment` = :idComment;";
                                $result = QueryPDO::getInstance()->query($requete, $tabParams);

                                if($result!=NULL)
                                    return QueryPDO::getInstance()->ServiceReturnJson("0","Ok");
                                else
                                    return QueryPDO::getInstance()->ServiceReturnJson("7","Nothing to update");

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