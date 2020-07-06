<?php
/**
 * @Project Traces - ModePaiement
 * @author Emie Pelletier <emiepelletier@hotmail.com>
 * @date Novembre 2019 - Version 1.0.1
 */

namespace App\Modeles;

use App\App;
use \PDO;

class ModePaiement
{
    private static $id_mode_paiement;
    private $est_paypal;
    private $nom_complet;
    private $no_carte;
    private $type_carte;
    private $date_expiration_carte;
    private $code;
    private $est_defaut;


    public function __construct()
    {
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            return $this->$property = $value;
        }
    }

    /*
    * @method inserer
    * @desc Ajoute les informations concernant le paiement dans la BD
    */
    public function inserer()
    {
        // Récupération de l'objet PDO
        $pdo = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $sql = "INSERT INTO t_mode_paiement (est_paypal, nom_complet, no_carte, type_carte, date_expiration_carte, code, est_defaut)
                VALUES (:est_paypal, :nom_complet, :no_carte, :type_carte, :date_expiration_carte, :code, :est_defaut)";

        // Préparation de la requête
        $requete = $pdo->prepare($sql);

        $requete->bindParam(':est_paypal', $this->est_paypal, PDO::PARAM_INT, 1);
        $requete->bindParam(':nom_complet', $this->nom_complet, PDO::PARAM_STR, 100);
        $requete->bindParam(':no_carte', $this->no_carte, PDO::PARAM_STR, 255);
        $requete->bindParam(':type_carte', $this->type_carte, PDO::PARAM_STR, 255);
        $requete->bindParam(':date_expiration_carte', $this->date_expiration_carte, PDO::PARAM_STR, 255);
        $requete->bindParam(':code', $this->code, PDO::PARAM_INT, 10);
        $requete->bindParam(':est_defaut', $this->est_defaut, PDO::PARAM_INT, 1);

        // Exécution de la requête
        $requete->execute();

        // Récupération de la dernière donnée entrée dans la BD pour trouver l'ID et l'associer correctement
        $id = $pdo->lastInsertId();
        $this->id_mode_paiement = $id;
    }
}