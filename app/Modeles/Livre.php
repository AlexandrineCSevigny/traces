<?php
/**
 * @Project Traces- Livre
 * @author Camille Dion-Bolduc <camille.dion.bolduc@gmail.com>
 * @author Alexandrine C. Sevigny <asevigny@outlook.fr>
 * @author Emie Pelletier <emiepelletier@hotmail.com>
 * @date Septembre 2019 - Version 1.0.1
 */
declare(strict_types=1);

namespace App\Modeles;

use App\App;
use App\Utilitaires;
use \PDO;

class Livre

{
    private $id;
    private $nbre_pages;
    private $est_illustre;
    private $annee_publication;
    private $langue;
    private $prix;
    private $titre;
    private $sous_titre;
    private $mots_cles;
    private $isbn;
    private $description;
    private $autres_caracteristiques;
    private $est_coup_de_coeur;
    private $parution_id;
    private $collection_id;


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
    * @method trouverTout
    * @desc Trouver tous les livres
    * @return array - Tableau de livres
    */
    public static function trouverTout(): array
    {
        // Récupération de l'objet PDO
        $connexionPDO = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $chaineSQL = 'SELECT * 
                      FROM livres';

        //Préparation de la requête
        $requetePreparee = $connexionPDO->prepare($chaineSQL);

        //Définit le mode de récupération
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Livre::class);

        //Exécution de la requête
        $requetePreparee->execute();

        //Récupération des résultats
        $livres = $requetePreparee->fetchAll();

        return $livres;
    }

    /*
   * @method getParution
   * @desc Trouver la parution reliée à un livre
   * @return Parution - Parution (disponible, nouveauté, épuisé ou à paraitre)
   */
    public function getParution(): Parution
    {
        return Parution::trouver($this->parution_id);
    }

    /*
  * @method getAuteurs
  * @desc Trouver les auteurs reliés à un livre
  * @return array Auteurs
  */
    public function getAuteurs(): array
    {
        return Auteur::trouverParLivre($this->id);
    }

    /*
    * @method getEditeur
    * @desc Trouver les éditeurs reliés à un livre
    * @return array éditeurs
    */
    public function getEditeur(): array
    {
        return Editeur::trouverParLivre($this->id);
    }

    /*
   * @method getCollection
   * @desc Trouver les collections reliés à un livre
   * @return array collections
   */
    public function getCollection(): array
    {
        return Collection::trouverParLivre($this->id);
    }

    /*
  * @method getHonneurs
  * @desc Trouver les honneurs reliés à un livre
  * @return array honneurs
  */
    public function getHonneurs(): array
    {
        return Honneur::trouverParLivre($this->id);
    }

    /*
    * @method getRecensions
    * @desc Trouver les recencions reliés à un livre
    * @return array recensions
    */
    public function getRecensions(): array
    {
        return Recension::trouverParLivre($this->id);
    }

    /*
    * @method formaterIsbn
    * @desc Appel la méthode utilitaire ISBNToEAN
    * @return string - isbn formaté
    */
    public function formaterIsbn(): string
    {
        $isbn = Utilitaires::ISBNToEAN($this->isbn);

        if (file_exists("liaisons/images/couvertures_livres/L" . $isbn . "1.jpg")) {
            $isbn = "L" . $isbn . "1";
        } else {
            $isbn = "placeholder";
        }

        return $isbn;
    }

    /*
    * @method formaterPrix
    * @desc Appel la méthode utilitaire formaterPrix
    * @return string - prix formaté
    */
    public function formaterPrix(): string
    {
        $prix = Utilitaires::formaterPrix($this->prix);
        return $prix;
    }

    /*
   * @method formaterTitre
   * @desc Appel la méthode utilitaire formaterTitre
   * @return string - prix formaté
   */
    public function formaterTitre():string
    {
        $titre = Utilitaires::formaterTitre($this->titre);
        return $titre;
    }

    /*
   * @method compter
   * @desc Compte le nombre total de livres
   * @return string - Nombre total
   */
    public static function compter(): string
    {
        // Récupération de l'objet PDO
        $connexionPDO = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $chaineSQL = 'SELECT COUNT(id) as total 
                      FROM livres';

        //Préparation de la requête
        $requetePreparee = $connexionPDO->prepare($chaineSQL);

        //Exécution de la requête
        $requetePreparee->execute();

        //Récupération des résultats
        $nombreLivres = $requetePreparee->fetch();

        return $nombreLivres['total'];
    }

    /*
  * @method compterFiltre
  * @desc Compte le nombre total de livres filtrés par une catégorie
  * @param string - Catégorie sélectionnée
  * @return string - Nombre total
  */
    public static function compterFiltre(string $categorie): string
    {
        // Récupération de l'objet PDO
        $connexionPDO = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $chaineSQL = 'SELECT COUNT(categorie_id) as total 
                      FROM livres 
                      INNER JOIN categories_livres ON livres.id = categories_livres.livre_id
                      WHERE categories_livres.categorie_id= :categorie';

        //Préparation de la requête
        $requetePreparee = $connexionPDO->prepare($chaineSQL);
        $requetePreparee->bindParam(':categorie',
            $categorie, PDO::PARAM_INT);

        //Exécution de la requête
        $requetePreparee->execute();

        //Récupération des résultats
        $nombreLivresFiltres = $requetePreparee->fetch();

        return $nombreLivresFiltres['total'];
    }

    /*
    * @method compterNouveautes
    * @desc Compte le nombre total de livres nouveaux
    * @return string - Nombre total
    */
    public static function compterNouveautes(): string
    {
        // Récupération de l'objet PDO
        $connexionPDO = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $chaineSQL = 'SELECT COUNT(parution_id) as total 
                      FROM livres 
                      WHERE parution_id=3';

        //Préparation de la requête
        $requetePreparee = $connexionPDO->prepare($chaineSQL);

        //Exécution de la requête
        $requetePreparee->execute();

        //Récupération des résultats
        $nombreLivresNouveaux = $requetePreparee->fetch();

        return $nombreLivresNouveaux['total'];
    }

    /*
    * @method trouverParLimite
    * @desc Trouve tous les livres en fonction d'une quantité et des tris
    * @param int - index débutant pour chercher une quantité de livres
    * @param int - quantité de livres à trouver
    * @param string - tri sélectionné (titre ou prix)
    * @param string - ordre sélectionné (ASC ou DESC)
    * @return array - livres
    */
    public static function trouverParLimite(int $unIndex, int $uneQte, string $tri, string $ordre): array
    {
        // Récupération de l'objet PDO
        $connexionPDO = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $chaineSQL = 'SELECT *
                          FROM livres
                          ORDER BY ' . $tri . ' ' . $ordre . '
                          LIMIT :indexPremier, :quantite';

        //Préparation de la requête
        $requetePreparee = $connexionPDO->prepare($chaineSQL);

        $requetePreparee->bindParam(':indexPremier', $unIndex, PDO::PARAM_INT);
        $requetePreparee->bindParam(':quantite', $uneQte, PDO::PARAM_INT);

        //Définit le mode de récupération
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Livre::class);

        //Exécution de la requête
        $requetePreparee->execute();

        //Récupération des résultats
        $quantiteLivre = $requetePreparee->fetchAll();

        return $quantiteLivre;
    }

    /*
   * @method trouverParCategorie
   * @desc Trouve tous les livres en fonction d'une catégorie sélectionnée
   * @param int - id de la catégorie sélectionnée
   * @param int - index débutant pour chercher une quantité de livres
   * @param int - quantité de livres à trouver
   * @param string - tri sélectionné (titre ou prix)
   * @param string - ordre sélectionné (ASC ou DESC)
   * @return array - livres filtrés
   */
    public static function trouverParCategorie(string $categorie, int $unIndex, int $uneQte, string $tri,  string $ordre): array
    {
        // Récupération de l'objet PDO
        $connexionPDO = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $chaineSQL = 'SELECT livres.id, livres.titre, livres.prix, livres.isbn, livres.parution_id, livres.est_coup_de_coeur
                      FROM livres 
                      INNER JOIN categories_livres ON livres.id = categories_livres.livre_id
                      WHERE categories_livres.categorie_id= :categorie
                      ORDER BY ' . $tri . ' ' . $ordre . '
                      LIMIT :indexPremier, :quantite';

        //Préparation de la requête
        $requetePreparee = $connexionPDO->prepare($chaineSQL);

        $requetePreparee->bindParam(':categorie', $categorie, PDO::PARAM_INT);
        $requetePreparee->bindParam(':indexPremier', $unIndex, PDO::PARAM_INT);
        $requetePreparee->bindParam(':quantite', $uneQte, PDO::PARAM_INT);

        //Définit le mode de récupération
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Livre::class);

        //Exécution de la requête
        $requetePreparee->execute();

        //Récupération des résultats
        $livresFiltresParCategorie = $requetePreparee->fetchAll();

        return  $livresFiltresParCategorie;
    }

    /*
 * @method trouverParCategorie
 * @desc Trouve tous les livres en fonction d'une catégorie sélectionnée
 * @param int - id de la catégorie sélectionnée
 * @param int - index débutant pour chercher une quantité de livres
 * @param int - quantité de livres à trouver
 * @param string - tri sélectionné (titre ou prix)
 * @param string - ordre sélectionné (ASC ou DESC)
 * @return array - livres filtrés
 */
    public static function trouverParNouveaute(int $unIndex, int $uneQte, string $tri,  string $ordre): array
    {
        // Récupération de l'objet PDO
        $connexionPDO = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $chaineSQL = 'SELECT livres.id, livres.titre, livres.prix, livres.isbn, livres.parution_id, livres.est_coup_de_coeur
                      FROM livres 
                      WHERE parution_id=3
                      ORDER BY ' . $tri . ' ' . $ordre . '
                      LIMIT :indexPremier, :quantite';

        //Préparation de la requête
        $requetePreparee = $connexionPDO->prepare($chaineSQL);

        $requetePreparee->bindParam(':indexPremier', $unIndex, PDO::PARAM_INT);
        $requetePreparee->bindParam(':quantite', $uneQte, PDO::PARAM_INT);

        //Définit le mode de récupération
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Livre::class);

        //Exécution de la requête
        $requetePreparee->execute();

        //Récupération des résultats
        $livresFiltresParNouveaute = $requetePreparee->fetchAll();

        return $livresFiltresParNouveaute;
    }

    /*
  * @method trouverNouveautes
  * @desc Trouve tous les nouveautés
  * @return array - livres nouveaux
  */
    public static function trouverNouveautes():array
    {
        // Récupération de l'objet PDO
        $connexionPDO = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $chaineSQL = 'SELECT DISTINCT *
                      FROM livres 
                      WHERE parution_id=3';

        //Préparation de la requête
        $requetePreparee = $connexionPDO->prepare($chaineSQL);

        //Définit le mode de récupération
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Livre::class);

        //Exécution de la requête
        $requetePreparee->execute();

        //Récupération des résultats
        $nouveautes = $requetePreparee->fetchAll();

        return $nouveautes;
    }

    /*
    * @method trouverCoupDeCoeur
    * @desc Trouve tous les coups de coeur
    * @return array - livres coups de coeur
    */
    public static function trouverCoupDeCoeur():array
    {
        // Récupération de l'objet PDO
        $connexionPDO = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $chaineSQL = 'SELECT DISTINCT * 
                      FROM livres 
                      WHERE est_coup_de_coeur=1';

        //Préparation de la requête
        $requetePreparee = $connexionPDO->prepare($chaineSQL);

        //Définit le mode de récupération
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Livre::class);

        //Exécution de la requête
        $requetePreparee->execute();

        //Récupération des résultats
        $coupsdecoeur = $requetePreparee->fetchAll();

        return $coupsdecoeur;
    }

    /*
    * @method trouverParIsbn
    * @desc Trouve tous les livres par ISBN
    * @param string - un isbn
    * @return array - livres
    */
    public static function trouverParIsbn(string $unIsbn):Livre
    {
        $sql = "SELECT *
                FROM livres
                WHERE isbn = :isbn";

        $requete = App::getInstance()->getPDO()->prepare($sql);
        $requete->bindParam(':isbn', $unIsbn, PDO::PARAM_STR);

        // Définir le mode de récupération
        $requete->setFetchMode(PDO::FETCH_CLASS, Livre::class);

        // Exécuter la requête
        $requete->execute();

        // Récupérer une seule occurrence à la fois
        $livre = $requete->fetch();

        return $livre;
    }

    /*
  * @method trouverParMotscle
  * @desc Trouver les livres en fonction d'un champ texte
  * @param string - un mot clé
  * @return array - Un array de livres
  */
    public static function trouverParMotscle(string $motCle, string $valeurListe):array{

        // Récupération de l'objet PDO
        $connexionPDO = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $chaineSQL = "SELECT ".$valeurListe."
                      FROM livres
                      WHERE ".$valeurListe." LIKE '%".$motCle."%' LIMIT 0,10";

        //Préparation de la requête
        $requetePreparee = $connexionPDO->prepare($chaineSQL);

        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Livre::class);

        //Exécution de la requête
        $requetePreparee->execute();

        //Récupération des résultats
        $livres = $requetePreparee->fetchAll();

        return $livres;
    }
}