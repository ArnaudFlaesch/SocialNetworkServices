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
                $tabParams[':idutilisateur'] = $idUser;
                $requete = "SELECT P.idpost, P.post_content , P.post_date, U.user_firstname, U.user_name
                            FROM post P, socialnetwork.user U
                            WHERE P.iduser IN (SELECT idfriend FROM friend F WHERE F.iduser = :iduser AND F.friend_accepted = 1)
                            OR P.iduser IN (SELECT iduser FROM friend F WHERE F.idfriend = :idfriend AND F.friend_accepted = 1)
                            OR P.iduser = :idutilisateur
                            AND P.iduser = U.iduser
                            ORDER BY post_date DESC
                            LIMIT :limit OFFSET :offset";
                $result = QueryPDO::getInstance()->query($requete, $tabParams);
                if ($result != null) {
                    $i = 0;
                    $tabParams = null;
                    while ($ligne = $result->fetch(PDO::FETCH_ASSOC)) {
                        $tabParams[':idpost'] = $ligne['idpost'];
                        $lignes[] = $ligne;
                        
                        $requete = "SELECT L.idlike, L.like_date, U.user_firstname, U.user_name FROM socialnetwork.like L, socialnetwork.user U WHERE idpost = :idpost AND U.iduser = L.iduser";
                        $resultat = QueryPDO::getInstance()->query($requete, $tabParams);
                        if ($resultat != null) {
                            $like = 0;
                            while ($line = $resultat->fetch(PDO::FETCH_ASSOC)) {
                                $lignes[$i]['like #'.$like] = $line;
                                $like++;
                            }
                        }
                        
                        $requete = "SELECT T.idtag, T.iduser AS Taggeur, T.tag_date, T.user_tagged AS Tagged, U.user_firstname AS TagPrenom, U.user_name AS TagNom
                                    FROM socialnetwork.tag T, socialnetwork.user U WHERE idpost = :idpost AND U.iduser = T.user_tagged";
                        $resultat = QueryPDO::getInstance()->query($requete, $tabParams);
                        if ($resultat != null) {
                            $tag = 0;
                            while ($line = $resultat->fetch(PDO::FETCH_ASSOC)) {
                                $lignes[$i]['tag #'.$tag] = $line;
                                $tag++;
                            }
                        }
                        
                        $requete = "SELECT C.idcomment, C.comment_content, C.comment_date, U.user_firstname, U.user_name FROM socialnetwork.comment C, socialnetwork.user U WHERE idpost = :idpost AND U.iduser = C.iduser";
                        $resultat = QueryPDO::getInstance()->query($requete, $tabParams);
                        
                        if ($resultat != null) {
                            while ($line = $resultat->fetch(PDO::FETCH_ASSOC)) {
                                $comment = 0;
                                $tabComment[':idcomment'] = $line['idcomment'];
                                
                                $requeteComment = "SELECT L.idlike, L.like_date, U.user_firstname, U.user_name FROM socialnetwork.like L, socialnetwork.user U WHERE idcomment = :idcomment AND U.iduser = L.iduser";
                                $resultatComment = QueryPDO::getInstance()->query($requeteComment, $tabComment);
                                if ($resultatComment != null) {
                                    while ($lineComment = $resultatComment->fetch(PDO::FETCH_ASSOC)) {
                                        $line[] = $lineComment;
                                    }
                                }
                                
                                $requeteComment = "SELECT T.idtag, T.iduser AS Taggeur, T.tag_date, T.user_tagged AS Tagged, U.user_firstname AS TagPrenom, U.user_name AS TagNom
                                                   FROM socialnetwork.tag T, socialnetwork.user U WHERE idcomment = :idcomment AND U.iduser = T.user_tagged";
                                $resultatComment = QueryPDO::getInstance()->query($requeteComment, $tabComment);
                                if ($resultatComment != null) {
                                    
                                    while ($lineComment = $resultatComment->fetch(PDO::FETCH_ASSOC)) {
                                        $line[] = $lineComment;
                                    }
                                }
                                
                                $lignes[$i]['comment #'.$comment] = $line;
                                $comment++;
                            }
                        }
                        $i++;
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
            return QueryPDO::getInstance()->ServiceReturnJson("2","No token");
        }
    }
    else {
        return QueryPDO::getInstance()->ServiceReturnJson("5","Wrong Request Method");
    }
?>