<?php
/**
 * @Project Traces - ControleurLivre
 * @author Alexandrine C. Sevigny <asevigny@outlook.fr>
 * @author Emie Pelletier <emiepelletier@hotmail.com>
 * @date Septembre 2019 - Version 1.0.1
 */
declare(strict_types=1);

namespace App\Controleurs;

use App\App;
use App\Modeles\Livre;
use App\Utilitaires;
use \DateTime;
use App\Cookie;

class ControleurPanier
{
    private $blade = null;
    private $cookie = null;
    private $session = null;
    private $sessionPanier = null;

    public function __construct()
    {
        $this->blade = App::getInstance()->getBlade();
        $this->cookie = App::getInstance()->getCookie();
        $this->session = App::getInstance()->getSession();
        $this->sessionPanier = App::getInstance()->getSessionPanier();
    }

    // Ajout d'un livre dans le panier
    public function ajouterItem()
    {
        $pattern = '#^[0123]-[0-9]{1,7}-[0-9]{1,}-[0-9|a-z]$#i';
        $quantite = 1;
        // Récupérer le isbn
        if (isset($_GET['isbn'])) {
            $pregMatch = preg_match($pattern, $_GET['isbn']);
            if ($pregMatch == 1) {
                $isbn = $_GET['isbn'];
            } else {
                $isbn = -1;
            }
        }

        //Récupérer la quantité
        if (isset($_POST['quantite'])) {
            $pregMatch = preg_match('#^[0-9]{1,2}$#', $_POST['quantite']);
            if ($pregMatch == 1) {
                $quantite = (int)$_POST['quantite'];
            }
        }

        $livre = Livre::trouverParIsbn((string)$isbn);

        $this->sessionPanier->ajouterItem($livre, $quantite);

        // session panier crée message
        $this->sessionPanier->creerMessage($quantite);

        // Redirection si on a été dans le catalogue ou non
        if ($this->session->getItem('categorie')) {
            header("Location: index.php?controleur=livre&action=fiche&isbn=$livre->isbn");
            exit;
        } else {
            $categorie = $_GET['categorie'];
            header("Location: index.php?controleur=livre&action=fiche&isbn=$livre->isbn&categorie=$categorie");
            exit;
        }
    }

    // Afficher la fiche du panier
    public function fiche()
    {
        // Récupérer les information du panier
        $livre = $this->sessionPanier->getItems();
        $nbTotal = $this->sessionPanier->getNombreTotalItemsDifferents();
        $sousTotal = $this->sessionPanier->formaterPrix($this->sessionPanier->getMontantSousTotal());
        $tps = $this->sessionPanier->formaterPrix($this->sessionPanier->getMontantTPS());

        $total = $this->sessionPanier->formaterPrix($this->sessionPanier->getMontantTotal());

        // Récupérer la date estimée de livraison
        $date = $this->sessionPanier->getDateEstimee();
        $dateEstimee = Utilitaires::formaterDate($date);

        // On met le mode de livraison à standard si le sous-total ne permet plus gratuit
        if ((int)$sousTotal < 50 && $this->session->getItem('livraison')->mode_livraison == 'gratuit') {
            $this->sessionPanier->setModeLivraison('standard');
        }
        $livraison = $this->sessionPanier->formaterPrix($this->sessionPanier->getMontantFraisLivraison());


        $this->session->setItem('nomPage', 'panier');

        $tDonnees = array("livres" => $livre);
        $tDonnees = array_merge($tDonnees, array("nbTotal" => "$nbTotal"));
        $tDonnees = array_merge($tDonnees, array("sousTotal" => "$sousTotal"));
        $tDonnees = array_merge($tDonnees, array("tps" => "$tps"));
        $tDonnees = array_merge($tDonnees, array("livraison" => "$livraison"));
        $tDonnees = array_merge($tDonnees, array("total" => "$total"));
        $tDonnees = array_merge($tDonnees, array("panier" => $this->sessionPanier->getNombreTotalItems()));
        $tDonnees = array_merge($tDonnees, array("delai" => $dateEstimee));

        echo $this->blade->run("panier.fiche", $tDonnees);
    }

    // Mise à jour de la quantité d'un item au panier
    public function majQuantite()
    {
        $pattern = '#^[0123]-[0-9]{1,7}-[0-9]{1,}-[0-9|a-z]$#i';
        $quantite = 0;

        // Récupérer le isbn
        if (isset($_GET['isbn'])) {
            $pregMatch = preg_match($pattern, $_GET['isbn']);
            if ($pregMatch == 1) {
                $isbn = (string)$_GET['isbn'];
            } else {
                $isbn = -1;
            }
        }

        // Si on n'a pas de JS
        if (isset($_GET['pasJs'])) {

            // Récupérer la quantité
            if (isset($_POST['quantite'])) {
                $pregMatch = preg_match('#^[0-9]{1,2}$#', $_POST['quantite']);
                if ($pregMatch == 1) {
                    $quantite = (int)$_POST['quantite'];
                }
            }
            // On met à jour l"info dans le panier
            $this->sessionPanier->setQuantiteItem($isbn, $quantite);

            // Redirection
            header("Location: index.php?controleur=panier&action=fiche");
            exit;

        } else {
            // Récupérer la quantité
            if (isset($_GET['quantite'])) {
                $pregMatch = preg_match('#^[0-9]{1,2}$#', $_GET['quantite']);
                if ($pregMatch == 1) {
                    $quantite = (int)$_GET['quantite'];
                }
            }
            // On met à jour l'info dans le panier
            $this->sessionPanier->setQuantiteItem($isbn, $quantite);

            // Puisqu'on est en Ajax, on recalcule le panier et renvoie l'info
            $montantItem = $this->sessionPanier->formaterPrix($this->sessionPanier->getMontantItem($isbn));
            $nbItems = $this->sessionPanier->getNombreTotalItems();

            // Si le sous-total est plsu grand que 50 > livraison = gratuit
            if ( (int)$this->sessionPanier->getMontantSousTotal() >= 50 ) {
                $this->sessionPanier->setModeLivraison('gratuit');
            } else {
                $this->sessionPanier->setModeLivraison('standard');
            }
            // Récupérer la date estimée de livraison
            $date = $this->sessionPanier->getDateEstimee();
            $dateEstimee = Utilitaires::formaterDate($date);

            $arrInfo['delai'] = $dateEstimee;
            $arrInfo = $this->sessionPanier->recalculerPanier();
            $arrInfo["montantItem"] = $montantItem;
            $arrInfo['nbItems'] = $nbItems;

            echo json_encode($arrInfo);
        }
    }

    // On met à jour le mode de livraison
    public function majLivraison()
    {
        $pattern = '#^standard|prioritaire|gratuit$#';
        $livraison = null;

        // Récupérer le mode de livraison en ajax
        if ( isset($_GET['livraison']) ) {
            $pregMatch = preg_match($pattern, $_GET['livraison']);
            if ($pregMatch == 1) {
                $livraison = (string)$_GET['livraison'];
            } else {
                $livraison = -1;
            }

            $this->sessionPanier->setModeLivraison($livraison);
            $arrInfo = $this->sessionPanier->recalculerPanier();

            // Récupérer la date estimée de livraison
            $date = $this->sessionPanier->getDateEstimee();
            $dateEstimee = Utilitaires::formaterDate($date);
            $arrInfo['delai'] = $dateEstimee;

            echo json_encode($arrInfo);

        } else {
            // Sans js
            $pregMatch = preg_match($pattern, $_POST['livraison']);
            if ($pregMatch == 1) {
                $livraison = (string)$_POST['livraison'];
            } else {
                $livraison = -1;
            }

            $this->sessionPanier->setModeLivraison($livraison);

            header("Location: index.php?controleur=panier&action=fiche");
            exit;

        }
    }

    // On supprime un item du panier
    public function supprimerItem()
    {
        $pattern = '#^[0123]-[0-9]{1,7}-[0-9]{1,}-[0-9|a-z]$#i';

        // Récupérer le isbn
        if (isset($_GET['isbn'])) {
            $pregMatch = preg_match($pattern, $_GET['isbn']);
            if ($pregMatch == 1) {
                $isbn = (string)$_GET['isbn'];
            } else {
                $isbn = -1;
            }
        }
        $this->sessionPanier->supprimerItem($isbn);

        header("Location: index.php?controleur=panier&action=fiche");
        exit;
    }
}