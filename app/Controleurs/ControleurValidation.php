<?php
/**
 * @Project Traces - ControleurValidation
 * @author Emie Pelletier <emiepelletier@hotmail.com>
 * @date Novembre 2019 - Version 1.0.1
 */

namespace App\Controleurs;

use App\App;
use App\Utilitaires;
use App\Modeles\Adresse;
use App\Modeles\Commande;
use App\Modeles\ModePaiement;
use App\Modeles\Province;

class ControleurValidation
{
    private $blade = null;
    private $session = null;
    private $sessionPanier = null;

    public function __construct()
    {
        $this->blade = App::getInstance()->getBlade();
        $this->session = App::getInstance()->getSession();
        $this->sessionPanier = App::getInstance()->getSessionPanier();
    }

    public function afficher(): void
    {
        $arrClient = $this->session->getItem('client');
        $arrLivraison = $this->session->getItem('arrLivraison');
        $arrFacturation = $this->session->getItem('arrFacturation');
        $arrModeLivraison = $this->session->getItem('livraison');
        $infosPanier = $this->sessionPanier;
        $livres = $this->sessionPanier->getItems();

        // On construit le panier
        $nbTotal = $this->sessionPanier->getNombreTotalItemsDifferents();
        $sousTotal = $this->sessionPanier->formaterPrix($this->sessionPanier->getMontantSousTotal());
        $tps = $this->sessionPanier->formaterPrix($this->sessionPanier->getMontantTPS());
        $total = $this->sessionPanier->formaterPrix($this->sessionPanier->getMontantTotal());

        // Récupérer la date estimée de livraison
        $date = $this->sessionPanier->getDateEstimee();
        $dateEstimee = Utilitaires::formaterDate($date);

        // Livraison
        if ((int)$sousTotal < 50 && $this->session->getItem('livraison')->mode_livraison == 'gratuit') {
            $this->sessionPanier->setModeLivraison('standard');
        }
        $livraison = $this->sessionPanier->formaterPrix($this->sessionPanier->getMontantFraisLivraison());
        $livres = $this->sessionPanier->getItems();

        // -------  Affichage de la province  ---------
        $provinceLivraison = Province::trouverProvince($arrLivraison['province']['valeur']);
        $provinceFacturation = Province::trouverProvince($arrFacturation['province']['valeur']);

        // -------  Affichage du numéro de carte  ---------
        if ($arrFacturation['modePaiement']['valeur'] == 'carteCredit') {
            $numeroCarteFormate = str_replace(' ', '', $arrFacturation['numeroCarte']['valeur']);
            $carteCache = str_replace(substr($numeroCarteFormate, 0, 12), 'XXXX XXXX XXXX ', $numeroCarteFormate);
        }


        // -------  Affichage du numéro de téléphone  ---------
        $indicatif = substr($arrClient->telephone, 0, 3);
        $troisChiffres = substr($arrClient->telephone, 4, 3);
        $quatreChiffres = substr($arrClient->telephone, 6, 4);
        $numeroTelFormate = '(' . $indicatif . ') ' . $troisChiffres . '-' . $quatreChiffres;

        $tDonnees = array();
        $tDonnees = array_merge($tDonnees, array("panier" => $this->sessionPanier->getNombreTotalItems()));
        $tDonnees = array_merge($tDonnees, array("arrClient" => $arrClient));
        $tDonnees = array_merge($tDonnees, array("arrLivraison" => $arrLivraison));
        $tDonnees = array_merge($tDonnees, array("arrFacturation" => $arrFacturation));
        $tDonnees = array_merge($tDonnees, array("arrModeLivraison" => $arrModeLivraison));
        $tDonnees = array_merge($tDonnees, array("provinceLivraison" => $provinceLivraison->nom));
        $tDonnees = array_merge($tDonnees, array("provinceFacturation" => $provinceFacturation->nom));
        $tDonnees = array_merge($tDonnees, array("nbTotal" => "$nbTotal"));
        $tDonnees = array_merge($tDonnees, array("sousTotal" => "$sousTotal"));
        $tDonnees = array_merge($tDonnees, array("tps" => "$tps"));
        $tDonnees = array_merge($tDonnees, array("livraison" => "$livraison"));
        $tDonnees = array_merge($tDonnees, array("delai" => $dateEstimee));
        $tDonnees = array_merge($tDonnees, array("total" => "$total"));
        $tDonnees = array_merge($tDonnees, array("numeroTelFormate" => $numeroTelFormate));
        $tDonnees = array_merge($tDonnees, array("infosPanier" => $infosPanier));
        $tDonnees = array_merge($tDonnees, array("livres" => $livres));

        if ($arrFacturation['modePaiement']['valeur'] == 'carteCredit') {
            $tDonnees = array_merge($tDonnees, array("carteCache" => $carteCache));
        }

        echo $this->blade->run("transaction.validation", $tDonnees);
    }

    public function supprimerItem(): void
    {
        $pattern = '#^[0123]-[0-9]{1,7}-[0-9]{1,}-[0-9|a-z]$#i';

        //--- Récupération du isbn à supprimer -----
        if (isset($_GET['isbn'])) {

            $pregMatch = preg_match($pattern, $_GET['isbn']);

            if ($pregMatch == 1) {
                $isbn = (string)$_GET['isbn'];
            } else {
                $isbn = -1;
            }
        }

        $this->sessionPanier->supprimerItem($isbn);

        $this->afficher();
    }

    public function inserer()
    {
        $arrClient = $this->session->getItem('client');
        $arrLivraison = $this->session->getItem('arrLivraison');
        $arrFacturation = $this->session->getItem('arrFacturation');
        $arrModeLivraison = $this->session->getItem('livraison');
        $livres = $this->sessionPanier->getItems();

        $panier = $this->sessionPanier;



        // -------  Récupération de données à insérer dans la BD -------

        //----- Adresse de livraison par défaut -----
        if ($arrLivraison['adresseLivraison']['valeur'] == 'on') {
            $defaut = 1;
        } else {
            $defaut = 0;
        }

        //----- PayPal -----
        if ($arrFacturation['modePaiement']['valeur'] == 'paypal') {
            $paypal = 1;
        } else {
            $paypal = 0;
        }

        if ($arrFacturation['modePaiement']['valeur'] == 'carteCredit') {

            //----- Type de carte -----
            switch ($arrFacturation['cartesCredit']['valeur']) {
                case 'visa' :
                    $typeCarte = 'VISA';
                    break;
                case 'masterCard' :
                    $typeCarte = 'Master Card';
                    break;
                case 'amex' :
                    $typeCarte = 'American Express';
                    break;
            }

            //----- Date expiration -----
            $dateExpiration = date("Y/m/d H:i:s", mktime(0, 0, 0, $arrFacturation['moisCarte']['valeur'], 01, $arrFacturation['anneeCarte']['valeur']));

            //-----------------  Affection des données valides vers l'objet Mode de paiement  -------------------
            $modePaiement = new ModePaiement();

            $modePaiement->est_paypal = $paypal;
            $modePaiement->nom_complet = $arrFacturation['nomCarte']['valeur'];
            $modePaiement->no_carte = str_replace(' ', '', $arrFacturation['numeroCarte']['valeur']);
            $modePaiement->type_carte = $typeCarte;
            $modePaiement->date_expiration_carte = $dateExpiration;
            $modePaiement->code = $arrFacturation['codeSecurite']['valeur'];
            $modePaiement->est_defaut = 1;

            $modePaiement->inserer();

        }
        elseif ($arrFacturation['modePaiement']['valeur'] == 'paypal') {

            $modePaiement = new ModePaiement();
            $modePaiement->est_paypal = $paypal;
            $modePaiement->nom_complet = NULL;
            $modePaiement->no_carte = NULL;
            $modePaiement->type_carte = NULL;
            $modePaiement->date_expiration_carte = NULL;
            $modePaiement->code = NULL;
            $modePaiement->est_defaut = 1;

            $modePaiement->inserer();
        }


        //-----------------------  Affection des données valides vers l'objet Adresse  --------------------------

        //--- Adresse de livraison ---
        $adresseLivraison = new Adresse();

        $adresseLivraison->prenom = $arrLivraison['prenom']['valeur'];
        $adresseLivraison->nom = $arrLivraison['nom']['valeur'];
        $adresseLivraison->adresse = $arrLivraison['adresse']['valeur'];
        $adresseLivraison->ville = $arrLivraison['ville']['valeur'];
        $adresseLivraison->code_postal = $arrLivraison['codePostal']['valeur'];
        $adresseLivraison->est_defaut = $defaut;
        $adresseLivraison->type_adresse = 'livraison';
        $adresseLivraison->abbr_province = $arrLivraison['province']['valeur'];
        $adresseLivraison->id_client = $arrClient->id_client;

        $adresseLivraison->inserer();

        //--- Adresse de facturation ---
        $adresseFacturation = new Adresse();

        $adresseFacturation->prenom = $arrFacturation['prenom']['valeur'];
        $adresseFacturation->nom = $arrFacturation['nom']['valeur'];
        $adresseFacturation->adresse = $arrFacturation['adresse']['valeur'];
        $adresseFacturation->ville = $arrFacturation['ville']['valeur'];
        $adresseFacturation->code_postal = $arrFacturation['codePostal']['valeur'];

        //-- si l'adresse de livraison n'est pas celle par défaut (non coché), l'adresse de facturation est par défaut
        if ($defaut == 0) {
            $adresseFacturation->est_defaut = 1;
        } else {
            $adresseFacturation->est_defaut = 0;
        }

        $adresseFacturation->type_adresse = 'facturation';
        $adresseFacturation->abbr_province = $arrFacturation['province']['valeur'];
        $adresseFacturation->id_client = $arrClient->id_client;

        $adresseFacturation->inserer();


        //-----------------  Affection des données valides vers l'objet Commande  -------------------

        $commande = new Commande();

        $commande->etat = 'Nouvelle';
        $commande->date_commande = date('Y/m/d H:i:s');
        $commande->telephone = $arrClient->telephone;
        $commande->courriel = $arrClient->courriel;
        $commande->id_mode_paiement = $modePaiement->id_mode_paiement;
        $commande->id_mode_livraison = $arrModeLivraison->id_mode_livraison;
        $commande->id_taux = 4;
        $commande->id_adresse_livraison = $adresseLivraison->id_adresse;

        $commande->inserer();

        //---  Affection des items du panier dans une ligne de Commande  ---
        foreach ($livres as $livre) {
            //-- Création d'un tableau qui mémorise les infos pour chaque livre ---
            $itemCommande = ['isbn' => $livre->livre->isbn, 'prix' => $livre->livre->prix, 'quantite' => $livres[$livre->livre->isbn]->quantite, 'id_commande' => $commande->id_commande];

            //-- Envoie à Commande les éléments à inserer dans la BD ---
            $commande->insererLigneCommande($itemCommande);
        }

        // --- Redirection vers la page de confirmation ---
        header('Location: index.php?controleur=confirmation&action=afficher');
        exit;
    }
}