<?php
/**
 * @Project Traces - Editeur
 * @author Alexandrine C. Sevigny <asevigny@outlook.fr>
 * @date Septembre 2019 - Version 1.0.1
 */
declare(strict_types=1);

namespace App\Modeles;

use App\App;
use \PDO;

class Editeur
{
    private $id;
    private $nom;
    private $url;

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
    * @desc Trouver les editeurs d'un livre
    * @param string - ID de livre
    * @return array - Un array d'editeurs
    */
    public static function trouverParLivre( string $unIdLivre ): array
    {
        // Récupération de l'objet PDO
        $connexionPDO = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $chaineSQL = 'SELECT * 
                      FROM editeurs 
                      INNER JOIN editeurs_livres ON editeurs.id = editeurs_livres.editeur_id
                      WHERE editeurs_livres.livre_id=:unIdLivre';

        //Préparation de la requête
        $requetePreparee = $connexionPDO->prepare($chaineSQL);

        $requetePreparee->bindParam(':unIdLivre', $unIdLivre, PDO::PARAM_INT);

        //Définit le mode de récupération
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Editeur::class);

        //Exécution de la requête
        $requetePreparee->execute();

        //Récupération des résultats
        $editeurs = $requetePreparee->fetchAll();

        return $editeurs;
    }
}