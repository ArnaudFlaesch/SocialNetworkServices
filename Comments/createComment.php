<?php
    require_once("../QueryPDO.php");
    session_start();
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {     
        if (isset($_SESSION['token'])) {
            if(is_null($idUser = QueryPDO::getInstance()->getIdByToken($_SESSION["token"]))) {
                return QueryPDO::getInstance()->ServiceReturnJson("4","Invalid Token");
            }    
            else {
                    if (isset($_POST['contentComment']) && isset($_POST['idPost'])) {
                        $tabParams[':iduser'] = $idUser;
                        $tabParams[':contentComment'] = $_POST['contentComment'];
                        $tabParams[':dateComment'] = date("Y-m-d-H-i-s");
                        $tabParams[':idPost'] = $_POST['idPost'];
                        $requete = "INSERT INTO comment
                                    VALUES('', :contentComment, :dateComment, :iduser, :idPost)";
                        $result = QueryPDO::getInstance()->query($requete, $tabParams);
                        if ($result != null) {
                            $idcomment = QueryPDO::getPDOInstance()->lastInsertId();
                            if (isset($_POST['tags'])) {
                                $tabParams = null;
                                $tabParams[':iduser'] = $idUser;
                                $tabParams[':tag_date'] = date("Y-m-d-H-i-s");
                                $tabParams[':idcomment'] = $idcomment;
                                foreach ($_POST['tags'] as $user_tag) {
                                    $requete = "INSERT INTO tag (`idtag`, `iduser`, `tag_date`, `idcomment`, `user_tagged`) VALUES ('', :iduser, :tag_date, :idcomment, :user_tagged)";
                                    $tabParams[':user_tagged'] = $user_tag;
                                    QueryPDO::getInstance()->query($requete, $tabParams);
                                    $idTag = QueryPDO::getPDOInstance()->lastInsertId();
                                    $requete = "INSERT INTO notification (`idnotification`, `idtag`, `idcomment`) VALUES ('', :idtag, :idcomment)";
                                    $tabTag[':idtag'] = $idTag;
                                    $tabTag[':idcomment'] = $idcomment;
                                    QueryPDO::getInstance()->query($requete, $tabTag);
                                    $idNotification = QueryPDO::getPDOInstance()->lastInsertId();
                                    $requete = "INSERT INTO user_notification VALUES(:idnotification, :iduser, 0)";
                                    $tabNotification[':idnotification'] = $idNotification;
                                    $tabNotification[':iduser'] = $user_tag;
                                    QueryPDO::getInstance()->query($requete, $tabNotification);
                                }
                            }
                            return QueryPDO::getInstance()->ServiceReturnJson("0","Ok");
                        }
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