<?php
/**
 * @Project Traces - Adresse
 * @author Emie Pelletier <emiepelletier@hotmail.com>
 * @date Novembre 2019 - Version 1.0.1
 */

namespace App\Modeles;

use App\App;
use \PDO;

class Adresse
{
    private $id_adresse;
    private $prenom;
    private $nom;
    private $adresse;
    private $ville;
    private $code_postal;
    private $est_defaut;
    private $type_adresse;
    private $abbr_province;
    private $id_client;


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

    public static function trouverParClient(string $idClient)
    {
        // Récupération de l'objet PDO
        $connexionPDO = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $chaineSQL = 'SELECT t_adresse.id_adresse, t_adresse.prenom, t_adresse.nom, t_adresse.adresse, t_adresse.ville, t_adresse.code_postal, t_adresse.est_defaut, t_adresse.abbr_province
                    FROM t_adresse
                      WHERE t_adresse.id_client=:idClient && t_adresse.est_defaut=1';

        //Préparation de la requête
        $requetePreparee = $connexionPDO->prepare($chaineSQL);
        $requetePreparee->bindParam(':idClient', $idClient, PDO::PARAM_INT);

        //Définit le mode de récupération
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Adresse::class);

        //Exécution de la requête
        $requetePreparee->execute();

        //Récupération des résultats
        $adresse = $requetePreparee->fetch();

        return $adresse;
    }


    /*
   * @method inserer
   * @desc Ajoute les informations concernant les adresses dans la BD
   */
    public function inserer()
    {
        // Récupération de l'objet PDO
        $pdo = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $sql = "INSERT INTO t_adresse (prenom, nom, adresse, ville, code_postal, est_defaut, type_adresse, abbr_province, id_client)
                VALUES (:prenom, :nom, :adresse, :ville, :code_postal, :est_defaut, :type_adresse, :abbr_province, :id_client)";

        // Préparation de la requête
        $requete = $pdo->prepare($sql);

        $requete->bindParam(':prenom', $this->prenom, PDO::PARAM_STR, 255);
        $requete->bindParam(':nom', $this->nom, PDO::PARAM_STR, 255);
        $requete->bindParam(':adresse', $this-> adresse, PDO::PARAM_STR, 255);
        $requete->bindParam(':ville', $this->ville, PDO::PARAM_STR, 255);
        $requete->bindParam(':code_postal', $this->code_postal, PDO::PARAM_STR, 255);
        $requete->bindParam(':est_defaut', $this->est_defaut, PDO::PARAM_INT, 1);
        $requete->bindParam(':type_adresse', $this->type_adresse, PDO::PARAM_STR, 100);
        $requete->bindParam(':abbr_province', $this->abbr_province, PDO::PARAM_STR, 10);
        $requete->bindParam(':id_client', $this->id_client, PDO::PARAM_INT, 10);

        // Exécution de la requête
        $requete->execute();

        // Récupération de la dernière donnée entrée dans la BD pour trouver l'ID et l'associer correctement
        $id = $pdo->lastInsertId();
        $this->id_adresse = $id;
    }
}