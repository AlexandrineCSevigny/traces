<?php
/**
 * @Project Traces - Commande
 * @author Emie Pelletier <emiepelletier@hotmail.com>
 * @date Novembre 2019 - Version 1.0.1
 */

namespace App\Modeles;

use App\App;
use \PDO;

class Commande
{
    private $id_commande;
    private $etat;
    private $date_commande;
    private $telephone;
    private $courriel;
    private $id_mode_paiement;
    private $id_mode_livraison;
    private $id_taux;
    private $id_adresse_livraison;

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
   * @method inserer
   * @desc Ajoute les informations concernant la commande dans la BD
   */
    public function inserer()
    {
        // Récupération de l'objet PDO
        $pdo = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $sql = "INSERT INTO t_commande (etat, date_commande, telephone, courriel, id_mode_paiement, id_mode_livraison, id_taux, id_adresse_livraison)
                VALUES (:etat, :date_commande, :telephone, :courriel, :id_mode_paiement, :id_mode_livraison, :id_taux, :id_adresse_livraison)";

        // Préparation de la requête
        $requete = $pdo->prepare($sql);

        $requete->bindParam(':etat', $this->etat, PDO::PARAM_STR, 255);
        $requete->bindParam(':date_commande', $this->date_commande, PDO::PARAM_STR, 255);
        $requete->bindParam(':telephone', $this->telephone, PDO::PARAM_STR, 255);
        $requete->bindParam(':courriel', $this->courriel, PDO::PARAM_STR, 255);
        $requete->bindParam(':id_mode_paiement', $this->id_mode_paiement, PDO::PARAM_STR, 255);
        $requete->bindParam(':id_mode_livraison', $this->id_mode_livraison, PDO::PARAM_INT, 1);
        $requete->bindParam(':id_taux', $this->id_taux, PDO::PARAM_STR, 100);
        $requete->bindParam(':id_adresse_livraison', $this->id_adresse_livraison, PDO::PARAM_STR, 10);

        // Exécution de la requête
        $requete->execute();

        // Récupération de la dernière donnée entrée dans la BD pour trouver l'ID et l'associer correctement
        $id = $pdo->lastInsertId();
        $this->id_commande = $id;
    }

    public function insererLigneCommande($itemCommande)
    {

        // Récupération de l'objet PDO
        $pdo = App::getInstance()->getPDO();

        // Définition de la chaine SQL
        $sql = "INSERT INTO t_ligne_commande (isbn, prix, quantite, id_commande)
                VALUES (:isbn, :prix, :quantite, :id_commande)";

        // Préparation de la requête
        $requete = $pdo->prepare($sql);

        $requete->bindParam(':isbn', $itemCommande['isbn'], PDO::PARAM_STR, 100);
        $requete->bindParam(':prix', $itemCommande['prix'], PDO::PARAM_STR, 20);
        $requete->bindParam(':quantite', $itemCommande['quantite'], PDO::PARAM_INT, 5);
        $requete->bindParam(':id_commande', $itemCommande['id_commande'], PDO::PARAM_INT, 10);

        // Exécution de la requête
        $requete->execute();
    }
}

