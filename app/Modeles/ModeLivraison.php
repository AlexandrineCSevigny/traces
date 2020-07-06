<?php
/**
 * @Project Traces - ModeLivraison
 * @author Alexandrine C. Sevigny <asevigny@outlook.fr>
 * @date Septembre 2019 - Version 1.0.1
 */
declare(strict_types=1);

namespace App\Modeles;

use App\App;
use \PDO;

class ModeLivraison
{
    private $id_mode_livraison;
    private $date_mise_a_jour;
    private $mode_livraison;
    private $base;
    private $par_item;
    private $delai;
    private $delai_max_jrs;

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
    * @method trouverModeLivraison
    * @desc Trouver les données du mode standard
    * @return array - La ligne du mode standard
    */
    public static function trouverModeLivraison($mode): ModeLivraison
    {
        // Récupération de l'objet PDO
        $connexionPDO = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $chaineSQL = 'SELECT * 
                      FROM t_mode_livraison 
                      WHERE mode_livraison = :mode';

        //Préparation de la requête
        $requetePreparee = $connexionPDO->prepare($chaineSQL);

        $requetePreparee->bindParam(':mode', $mode, PDO::PARAM_STR);

        //Définit le mode de récupération
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, ModeLivraison::class);

        //Exécution de la requête
        $requetePreparee->execute();

        //Récupération des résultats
        $modeLivraison = $requetePreparee->fetch();

        return $modeLivraison;
    }
}