<?php
    require_once("../QueryPDO.php");
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_SESSION['token'])) {
            if(is_null($idUser = QueryPDO::getInstance()->getIdByToken($_SESSION["token"]))) {
                return QueryPDO::getInstance()->ServiceReturnJson("4","Invalid Token");
            }
            else {
                if (isset($_POST['idpost']) && isset($_POST['contentPost'])) {
                    $tabParams[':idpost'] = $_POST['idpost'];
                    $tabParams[':contentPost'] = $_POST['contentPost'];
                    $requete = "UPDATE post
                                SET post_content = :contentPost
                                WHERE idpost = :idpost";
                    $result = QueryPDO::getInstance()->query($requete, $tabParams);
                    if (isset($_POST['tags'])) {
                        $tabParams = null;
                        $tabParams[':iduser'] = $idUser;
                        $tabParams[':tag_date'] = date("Y-m-d-H-i-s");
                        $tabParams[':idpost'] = $_POST['idpost'];;
                        foreach ($_POST['tags'] as $user_tag) {
                            $requete = "INSERT INTO tag (`idtag`, `iduser`, `tag_date`, `idpost`, `user_tagged`) VALUES ('', :iduser, :tag_date, :idpost, :user_tagged)";
                            $tabParams[':user_tagged'] = $user_tag;
                            QueryPDO::getInstance()->query($requete, $tabParams);
                            $idTag = QueryPDO::getPDOInstance()->lastInsertId();
                            $requete = "INSERT INTO notification (`idnotification`, `idtag`) VALUES ('', :idtag)";
                            $tabTag[':idtag'] = $idTag;
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