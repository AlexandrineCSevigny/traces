<?php
/**
 * @Project Traces - Province
 * @author Emie Pelletier <emiepelletier@hotmail.com>
 * @date Novembre 2019 - Version 1.0.1
 */

namespace App\Modeles;

use App\App;
use \PDO;

class Province
{
    private $abbr_province;
    private $nom;
    private $lettres_code_postal;
    private $abbr_pays;

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
    * @method trouverProvince
    * @desc Trouver les informations concernant une province en fonction d'un abbréviation de province
    * @param string - abbrProvince
    * @return Province - Un objet Province
    */
    public static function trouverProvince(string $abbrProvince): Province
    {
        // Récupération de l'objet PDO
        $connexionPDO = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $chaineSQL = 'SELECT *
                      FROM t_province
                      WHERE t_province.abbr_province=:province';

        //Préparation de la requête
        $requetePreparee = $connexionPDO->prepare($chaineSQL);
        $requetePreparee->bindParam(':province', $abbrProvince, PDO::PARAM_STR);

        //Définit le mode de récupération
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Province::class);

        //Exécution de la requête
        $requetePreparee->execute();

        //Récupération des résultats
        $nomProvince = $requetePreparee->fetch();

        return $nomProvince;
    }
}