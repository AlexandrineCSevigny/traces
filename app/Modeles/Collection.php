<?php
/**
 * @Project Traces - ControleurLivre
 * @author Alexandrine C. Sevigny <asevigny@outlook.fr>
 * @date Septembre 2019 - Version 1.0.1
 */
declare(strict_types=1);

namespace App\Modeles;

use App\App;
use \PDO;

class Collection
{
    private $id;
    private $nom;
    private $description;

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
    * @desc Trouver la collection d'un livre
    * @param string - ID de livre
    * @return array - Un array de collection
    */
    public static function trouverParLivre( string $unIdLivre ): array
    {
        // Récupération de l'objet PDO
        $connexionPDO = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $chaineSQL = 'SELECT collections.id, collections.nom, collections.description 
                      FROM collections INNER JOIN livres 
                      ON livres.collection_id = collections.id
                      WHERE livres.id=:unIdLivre';

        //Préparation de la requête
        $requetePreparee = $connexionPDO->prepare($chaineSQL);

        $requetePreparee->bindParam(':unIdLivre', $unIdLivre, PDO::PARAM_INT);

        //Définit le mode de récupération
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Collection::class);

        //Exécution de la requête
        $requetePreparee->execute();

        //Récupération des résultats
        $collection = $requetePreparee->fetchAll();

        return $collection;
    }
}