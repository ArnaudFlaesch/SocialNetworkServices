<?php

$requete = "SELECT *
FROM post
ORDER BY (SELECT COUNT(*) FROM socialnetwork.like WHERE idUser = /TOKEN ) DESC";

