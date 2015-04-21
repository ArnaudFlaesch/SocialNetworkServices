<?php
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "PUT") {
        if (isset($_PUT[':contentPost'])) {
            $bdd = new PDO('mysql:host=localhost;dbname=socialnetwork','root','');
            $requete = $bdd->prepare("UPDATE post
                                      SET post_content = :contentPost
                                      WHERE idpost = :idPost ");
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