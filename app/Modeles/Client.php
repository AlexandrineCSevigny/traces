<?php
/**
 * @author : Emie Pelletier & Camille Dion-Bolduc
 * @version : 1.0.2
 */

namespace App\Modeles;

use App\App;
use \PDO;

class Client {
    private $id_client;
    private $prenom;
    private $nom;
    private $courriel;
    private $telephone;
    private $mot_de_passe;
    private $id_adresse_facturation;

    public function __construct() {
        // Rien, pas besoin
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property; }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            return $this->$property = $value; }
    }

    /*
    * @method trouver
    * @desc Trouver un client en particulier avec un id
    * @param int Un id de client de la DB
    * @return classe - Retourne objet Client
    */
    public static function trouver(int $unIdClient): ?Client
    {
        $connexionPDO = App::getInstance()->getPDO();
        $chaineSQL = 'SELECT t_client.id, t_client.prenom, t_client.nom, t_client.courriel, t_client.telephone, t_client.telephone, t_client.id_adresse_facturation
                      FROM t_client
                      WHERE t_client.id = :idClient';
        $requetePreparee = $connexionPDO->prepare($chaineSQL);

        // On bind les paramètres sur les variables
        $requetePreparee->bindParam(':idClient', $unIdClient, PDO::PARAM_INT);
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Client::class);
        $requetePreparee->execute();
        $client = $requetePreparee->fetch();

        // On gère si le client n'existe pas, on retourne null
        if($client == false){
            $client = null; }

        return $client;
    }

    /*
    * @method trouverParCourriel
    * @desc Trouver un client en particulier avec un courriel
    * @param string Un courriel d'un formulaire
    * @return classe - Retourne objet Client ou null (*pas typé pour éviter bug avec Ajax)
    */
    static public function trouverParCourriel(string $unCourriel)
    {
        $pdo = App::getInstance()->getPDO();
        $sql = 'SELECT *
                FROM t_client
                WHERE t_client.courriel = :courriel';
        $requete = $pdo->prepare($sql);

        //On bind les paramètres sur les variables
        $requete->bindParam(':courriel', $unCourriel, PDO::PARAM_STR, 255);
        $requete->setFetchMode(PDO::FETCH_CLASS, Client::class);
        $requete->execute();
        $client = $requete->fetch();

        return $client;
    }

    /*
    * @method inserer
    * @desc Ajoute un client à la DB suite à un formulaire
    */
    public function insererNouveauClient($NCcourriel, $NCmot_de_passe, $NCtelephone, $NCnom, $NCprenom)
    {
        $pdo = App::getInstance()->getPDO();
        $sql = "INSERT INTO t_client (prenom, nom, courriel, telephone, mot_de_passe)
                VALUES (:prenom, :nom, :courriel, :telephone, :mot_de_passe)";
        $requete = $pdo->prepare($sql);

        // On bind les paramètres sur les variables
        $requete->bindParam(':prenom', $NCprenom, PDO::PARAM_STR, 255);
        $requete->bindParam(':nom', $NCnom, PDO::PARAM_STR, 255);
        $requete->bindParam(':courriel', $NCcourriel, PDO::PARAM_STR, 255);
        $requete->bindParam(':telephone', $NCtelephone, PDO::PARAM_INT, 10);
        $requete->bindParam(':mot_de_passe', $NCmot_de_passe, PDO::PARAM_STR, 250);
        $requete->execute();

        // On va chercher la dernière donnée rentrée dans la BD pour trouver l'ID et l'associer correctement
        $idNouveauClient = $pdo->lastInsertId();
        $this->id_client = $idNouveauClient;
    }

    /*
    * @method getAdresse
    * @desc Retrouve l'adresse par défaut qui est associé au client
    */
    public static function getAdresse($idClient)
    {
        $adresse = Adresse::trouverParClient($idClient);

        if ($adresse == false) {
            return null;
        } else {
            return $adresse;
        }
    }
}

