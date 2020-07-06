<?php
/**
 * @Project Traces - Recension
 * @author Alexandrine C. Sevigny <asevigny@outlook.fr>
 * @date Septembre 2019 - Version 1.0.1
 */
declare(strict_types=1);

namespace App\Modeles;

use App\App;
use App\Utilitaires;
use \PDO;


class Recension
{
    private $id;
    private $date;
    private $titre;
    private $nom_media;
    private $nom_journaliste;
    private $description;
    private $livre_id;

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
    * @desc Trouver les articles concernant un livre
    * @param string - ID de livre
    * @return array - Un array de recension
    */
    public static function trouverParLivre( string $unIdLivre): array
    {
        // Récupération de l'objet PDO
        $connexionPDO = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $chaineSQL = 'SELECT recensions.id, recensions.date, recensions.titre, recensions.nom_media, recensions.nom_journaliste, recensions.description 
                      FROM recensions INNER JOIN livres 
                      ON livres.id = recensions.livre_id
                      WHERE livres.id=:unIdLivre';

        //Préparation de la requête
        $requetePreparee = $connexionPDO->prepare($chaineSQL);

        $requetePreparee->bindParam(':unIdLivre', $unIdLivre, PDO::PARAM_INT);

        //Définit le mode de récupération
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Recension::class);

        //Exécution de la requête
        $requetePreparee->execute();

        //Récupération des résultats
        $recension = $requetePreparee->fetchAll();

        return $recension;
    }

    /*
    * @method formaterDate
    * @desc Formater l'affichage de date
    * @return string - Date formatée
    */
    public function formaterDate(): string {
        $date = Utilitaires::formaterDate($this->date);
        return $date;
    }

    /*
    * @method formaterTitre
    * @desc Appel la méthode utilitaire formaterTitre
    * @return string - prix formaté
    */
    public function formaterTitre(): string
    {
        $titre = Utilitaires::formaterTitre($this->titre);
        return $titre;
    }
}