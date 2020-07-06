<?php
/**
 * @Project Traces - Categorie
 * @author Emie Pelletier <emiepelletier@hotmail.com>
 * @date Septembre 2019 - Version 1.0.1
 */
declare(strict_types=1);

namespace App\Modeles;

use App\App;
use \PDO;

class Categorie
{
    private $id;
    private $nom_fr;
    private $nom_en;

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
   * @method trouverCategories
   * @desc Trouver la liste des catégories
   * @return array - Tableau des livres
   */
    public static function trouverCategories(): array
    {
        // Récupération de l'objet PDO
        $connexionPDO = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $chaineSQL = 'SELECT * 
                      FROM categories';

        //Préparation de la requête
        $requetePreparee = $connexionPDO->prepare($chaineSQL);

        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Categorie::class);

        //Exécution de la requête
        $requetePreparee->execute();

        //Récupération des résultats
        $categories = $requetePreparee->fetchAll();

        return $categories;
    }

    /*
  * @method trouver
  * @desc Trouver les livres en fonction de la catégorie
  * @param int - Catégorie sélectionnée
  * @return Categorie - Une catégorie
  */
    public static function trouver(string $idCategorie): Categorie
    {
        // Récupération de l'objet PDO
        $connexionPDO = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $chaineSQL = 'SELECT *
                      FROM categories
                      WHERE id=:idCategorie';

        //Préparation de la requête
        $requetePreparee = $connexionPDO->prepare($chaineSQL);
        $requetePreparee->bindParam(':idCategorie', $idCategorie, PDO::PARAM_INT);

        //Définit le mode de récupération
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Categorie::class);

        //Exécution de la requête
        $requetePreparee->execute();

        //Récupération des résultats
        $categorie = $requetePreparee->fetch();

        return $categorie;
    }
}