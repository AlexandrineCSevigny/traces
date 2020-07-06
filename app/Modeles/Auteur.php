<?php
/**
 * @Project Traces - Auteur
 * @author Alexandrine C. Sevigny <asevigny@outlook.fr>
 * @author Emie Pelletier <emiepelletier@hotmail.com>
 * @date Septembre 2019 - Version 1.0.1
 */

namespace App\Modeles;

use App\App;
use \PDO;

class Auteur
{
    private $id;
    private $nom;
    private $prenom;
    private $biographie;
    private $url_blogue;


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
    * @method trouverParLivre
    * @desc Trouver les auteurs concernant un livre
    * @param string - ID de livre
    * @return array - Un array d'auteur
    */
    public static function trouverParLivre(string $unIdLivre)
    {
        // Récupération de l'objet PDO
        $connexionPDO = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $chaineSQL = 'SELECT *
                      FROM auteurs 
                      INNER JOIN auteurs_livres ON auteurs.id = auteurs_livres.auteur_id
                      WHERE auteurs_livres.livre_id=:unIdLivre';

        //Préparation de la requête
        $requetePreparee = $connexionPDO->prepare($chaineSQL);

        $requetePreparee->bindParam(':unIdLivre', $unIdLivre, PDO::PARAM_INT);

        //Définit le mode de récupération
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Auteur::class);

        //Exécution de la requête
        $requetePreparee->execute();

        //Récupération des résultats
        $auteurs = $requetePreparee->fetchAll();

        return $auteurs;
    }


    /*
   * @method trouverParMotscle
   * @desc Trouver les auteurs en fonction d'un champ texte
   * @param string - un mot clé
   * @return array - Un array d'auteurs
   */
    public static function trouverParMotscle(string $motCle){

        // Récupération de l'objet PDO
        $connexionPDO = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $chaineSQL = "SELECT nom, prenom
                      FROM auteurs 
                      WHERE nom LIKE '%".$motCle."%'";

        //Préparation de la requête
        $requetePreparee = $connexionPDO->prepare($chaineSQL);

        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Auteur::class);

        //Exécution de la requête
        $requetePreparee->execute();

        //Récupération des résultats
        $auteurs = $requetePreparee->fetchAll();

        return $auteurs;
    }

    /*
    * @method getNomPrenom
    * @desc Concaténer le prenom et le nom d'un auteur
    * @return string - Le nom complet de l'auteur
    */
    public function getNomPrenom()
    {
        return $this->prenom . ' ' . $this->nom;

    }
}