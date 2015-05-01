<?php
    require_once("../QueryPDO.php");
    session_start();
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "PUT") {
        if(is_null($idUser = QueryPDO::getInstance()->getIdByToken($_SESSION["token"]))) {
            return QueryPDO::getInstance()->ServiceReturnJson("4","Invalid Token");
        }
        else {
            parse_str(file_get_contents('php://input', $_PUT));
            print_r($_PUT);
            if (isset($_PUT['idpost']) && isset($_PUT['contentPost'])) {
                $tabParams[':idpost'] = $_PUT['idpost'];
                $tabParams[':contentPost'] = $_PUT['contentPost'];
                $requete = "UPDATE post
                            SET contentpost = :contentPost
                            WHERE idpost = :idpost";
                $result = QueryPDO::getInstance()->query($requete, $tabParams);
                if ($result != null) {
                    $idpost = QueryPDO::getPDOInstance()->lastInsertId();
                    if (isset($_PUT['tags'])) {
                        $tabParams = null;
                        $tabParams[':iduser'] = $idUser;
                        $tabParams[':tag_date'] = date("Y-m-d-H-i-s");
                        $tabParams[':idpost'] = $idpost;
                        foreach ($_PUT['tags'] as $user_tag) {
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
            }
            else {
                return QueryPDO::getInstance()->ServiceReturnJson("1","Missing parameters");
            }
        }
    }
    else {
        return QueryPDO::getInstance()->ServiceReturnJson("5","Wrong Request Method");
    }
?>