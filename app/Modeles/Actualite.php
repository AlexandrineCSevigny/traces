<?php
/**
 * @project Traces - Griserie
 * @author Camille Dion-Bolduc <camille.dion.bolduc@gmail.com>
 * @version 1.0.2 - Vendredi 11 octobre
 */

namespace App\Modeles;

use App\App;
use App\Utilitaires;
use \PDO;
use \datetime;

class Actualite
{
    private $id;
    private $date;
    private $titre;
    private $texte_actualite;
    private $id_auteur;

    public function __construct()
    {
        // Rien
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
    * @method trouverActualites()
    * @desc On demande à la BD de nous sortir toutes les données sous la table Actualités, en ordre des plus récentes
    * @return string - Retourne un tableau de deux actualités, les plus récentes
    */
    public static function trouverActualites(): array {
        // Récupération de l'objet PDO
        $connexionPDO = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $chaineSQL = 'SELECT * 
                      FROM actualites
                      ORDER BY date DESC';

        //Préparation de la requête
        $requetePreparee = $connexionPDO->prepare($chaineSQL);
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Actualite::class);

        //Exécution de la requête
        $requetePreparee->execute();

        //Récupération des résultats
        $actualites = $requetePreparee->fetchAll();

        // Shuffle le tableau et en garder que deux au hasard
        array_splice($actualites, 2, 3);

        return $actualites; }

    /*
    * @method raccourcirActualites()
    * @desc On va le transformer en tableau avec la fonction explode()
    * @desc On va couper le tableau après 65 mots et on reforme le tableau avec implode()
    * @param string - Prend $texteActualite d'un article en particulier
    * @return string - Retourne le texte de l'actualité coupée, $actualiteCoupee
    */
    public function raccourcirActualites(string $texteActualite): string {
        $nbMots = 65;
        $actualiteCoupee = Utilitaires::raccourcirTexte($texteActualite, $nbMots);

        return $actualiteCoupee;
    }

    /*
    * @method formaterDate()
    * @desc On appelle utilitaires pour formater la date en texte et non en chiffres
    * @param string - Prend $date d'un article en particulier
    * @return string - Retourne la date formatée $dateFormatee
    */
    public function formaterDate(string $date): string
    {
        // On appelle formater date qui met la date en texte et non comme la BD
        $dateFormatee = Utilitaires::formaterDate($date);

        return $dateFormatee;
    }
}