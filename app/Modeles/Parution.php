<?php
/**
 * @Project Traces - Parution
 * @author Alexandrine C. Sevigny <asevigny@outlook.fr>
 * @date Septembre 2019 - Version 1.0.1
 */
declare(strict_types=1);

namespace App\Modeles;

use App\App;
use \PDO;

class Parution
{
    private $id;
    private $etat;

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
    * @method trouver
    * @desc Trouver la parution d'un livre
    * @param string - ID de livre
    * @return Parution - Une parution et ses infos
    */
    public static function trouver( string $unIdParition): Parution
    {
        // Récupération de l'objet PDO
        $connexionPDO = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $chaineSQL = 'SELECT *
                      FROM parutions 
                      WHERE parutions.id=:unIdParution';

        //Préparation de la requête
        $requetePreparee = $connexionPDO->prepare($chaineSQL);

        $requetePreparee->bindParam(':unIdParution', $unIdParition, PDO::PARAM_INT);

        //Définit le mode de récupération
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Parution::class);

        //Exécution de la requête
        $requetePreparee->execute();

        //Récupération des résultats
        $parution = $requetePreparee->fetch();

        return $parution;
    }
}