<?php

require_once('../vendor/autoload.php');

use App\App;

try {
    App::getInstance()->demarrer();
} catch (Throwable $e) {

    echo $e->getTraceAsString();

    //Journaliser dans un fichier les erreurs
//    error_log($e->getTraceAsString()."\n", 3, '../ressources/php-erreurs.log');

    echo "404";
    // redirection à faire vers la page 404
}