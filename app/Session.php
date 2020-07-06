<?php
/**
 * @Project Traces - Session
 * @author Emie Pelletier <emiepelletier@hotmail.com>
 * @date Septembre 2019 - Version 1.0.1
 */
declare(strict_types=1);

namespace App;

class Session
{

    public function __construct()
    {
    }

    // Gestion de la session
    public function demarrer():void
    {
        session_start(['cookie_lifetime' => 0]);
        // À zéro, la session va terminer après la fermeture du navigateur (sécurité).
    }

    public function supprimer():void
    {
        session_unset();  // Supprimer les variables dans le tableau $_SESSION
        session_destroy();  // Détruire les fichiers associés à l'identifiant de session sur le serveur.
        setcookie(session_name(),'',1);  // Demander au navigateur de supprimer le cookie contenant l’identifiant de session côté client.
    }

    public function regenererId():void
    {
        session_regenerate_id();
    }


    // Gestion des items

    public function setItem($uneCle, $uneValeur):void
    {
        $_SESSION[$uneCle] = $uneValeur;
    }

    public function getItem($uneCle)
    {
        $uneValeur = null;
        if(isset($_SESSION[$uneCle]))
        {
            $uneValeur = $_SESSION[$uneCle];
        }
        return $uneValeur;
    }

    public function supprimerItem($uneCle):void
    {
        unset( $_SESSION[$uneCle] );
    }

}