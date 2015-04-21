<?php
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "DELETE") {
        if (isset($_DELETE[':idPost'])) {
            $bdd = new PDO('mysql:host=localhost;dbname=socialnetwork','root','');
            $requete = $bdd->prepare("DELETE FROM user_notification WHERE idnotification IN
                                        (SELECT idNotification FROM notification WHERE idcomment IN
                                                (SELECT idcomment FROM comment WHERE idpost = :idpost) )
                                      DELETE FROM tag WHERE idpost = :idpost
                                      DELETE FROM comment WHERE idpost = :idpost
                                      DELETE FROM like WHERE idcomment IN (SELECT idcomment FROM comment WHERE idpost = :idpost)
                                      DELETE FROM like WHERE idpost = :idpost");
            $tabParams[':idpost'] = $_DELETE['idpost'];
            if ($requete && $requete->execute($tabParams)) {
                $codeRetour = 0;
            }
            unset($requete);
            unset($bdd);
        }
        else {
            $codeRetour = 1;
        }
    }
    else {
        $codeRetour = 6;
    }
?>