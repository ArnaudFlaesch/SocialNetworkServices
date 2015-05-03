<?php
    require_once("../QueryPDO.php");
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET") {
        $_SESSION['token'] = 1248;
        if (isset($_SESSION['token'])) {
            if(is_null($idUser = QueryPDO::getInstance()->getIdByToken($_SESSION["token"]))) {
                return QueryPDO::getInstance()->ServiceReturnJson("4","Invalid Token");
            }
            else {
                if (isset($_GET['hashtag'])) {
                    $tabParams[':iduser'] = $idUser;
                    $tabParams[':idfriend'] = $idUser;
                    $tabParams[':idutilisateur'] = $idUser;
                    $tabParams[':idutilisateur1'] = $idUser;
                    $tabParams[':idutilisateur2'] = $idUser;
                    $tabParams[':posthashtag'] = '%#'.$_GET['hashtag'].'%';
                    $tabParams[':commenthashtag'] = '%#'.$_GET['hashtag'].'%';
                    $requete = "SELECT P.* 
                                FROM post P, comment C
                                WHERE (post_content LIKE :posthashtag
                                        OR P.idpost IN (SELECT idpost
                                                        FROM comment
                                                        WHERE comment_content LIKE :commenthashtag
                                                        AND idpost IN (SELECT idpost 
                                                                       FROM post
                                                                       WHERE iduser IN ( (SELECT idfriend
                                                                                          FROM friend
                                                                                          WHERE iduser = :iduser AND friend_accepted = 1)
                                                                                    OR iduser IN (SELECT iduser FROM friend WHERE idfriend = :idfriend AND friend_accepted = 1) 
                                                                                        )
                                                                       )
                                                        )
                                       )
                                AND ( (P.iduser IN (SELECT idfriend FROM friend WHERE friend.iduser = :idutilisateur1 AND friend_accepted = 1)
                                                OR P.iduser IN (SELECT iduser FROM friend WHERE friend.idfriend = :idutilisateur2 AND friend_accepted = 1) )
                                OR P.iduser = :idutilisateur)
                                ORDER BY P.post_date DESC";
                    $result = QueryPDO::getInstance()->query($requete, $tabParams);
                    if ($result != null) {
                        $i = 1;
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