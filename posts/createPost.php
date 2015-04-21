<?php
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
        $bdd = new PDO('mysql:host=localhost;dbname=socialnetwork','root','');
        if (isset($_POST['contentPost']) && isset($_POST['idUser'])) {
            $tabParams[':contentPost'] = $_POST['contentPost'];
            $tabParams[':datePost'] = date("Y-m-d-H-i-s");
            $requete = $bdd->prepare("INSERT INTO post
                                      VALUES('', :contentPost, :datePost, :idUser) ");
            if ($requete && $requete->execute($tabParams)) {
                $codeRetour = 0;
            }
        }
        unset($requete);
        unset($bdd);
    }
?>