<?php
/**
 * @Project Traces - ControleurFacturation
 * @author Emie Pelletier <emiepelletier@hotmail.com>
 * @date Novembre 2019 - Version 1.0.1
 */

namespace App\Controleurs;

use App\App;
use \App\Utilitaires;


class ControleurFacturation
{

    private $blade = null;
    private $session = null;
    private $sessionPanier = null;
    private $arrLivraison = null;

    public function __construct()
    {
        $this->blade = App::getInstance()->getBlade();
        $this->session = App::getInstance()->getSession();
        $this->sessionPanier = App::getInstance()->getSessionPanier();
        $this->arrLivraison = $this->session->getItem('arrLivraison');
    }

    public function afficher(): void
    {
        // --- Récupération des données nouvellement entrées dans les champs ---
        $arrFacturation = $this->session->getItem('arrFacturation');

        // --- Suppression des données qui ont été stocké, utile pour une requête seulement ---
        $this->session->supprimerItem('arrFacturation');

        // --- Ajout à la session la page en cours pour la connexion ---
        $this->session->setItem('nomPage', 'facturation');

        $tDonnees = array("arrFacturation" => $arrFacturation);
        $tDonnees = array_merge($tDonnees, array("arrLivraison" => $this->arrLivraison));
        $tDonnees = array_merge($tDonnees, array("panier" => $this->sessionPanier->getNombreTotalItems()));
        echo $this->blade->run("transaction.facturation", $tDonnees);
    }

    public function valider(): void
    {
        //  --------------   Récupération du contenu des messages en format JSON   -------------------
        $contenuFichierJson = file_get_contents("../ressources/lang/fr_CA.UTF-8/messagesValidation.json");
        $tMessagesJson = json_decode($contenuFichierJson, true);

        //  ------------------------   Choix de la carte de crédit   -------------------------------
        if ($_POST['modePaiement'] == 'carteCredit') {
            if (isset($_POST['cartesCredit'])) {
                $carteSelectionne = $_POST['cartesCredit'];

                switch ($carteSelectionne) {
                    case 'visa':
                        $motif = "#^[4][0-9]{3}[ ]?[0-9]{4}[ ]?[0-9]{4}[ ]?[0-9]{4}$#";
                        break;
                    case 'masterCard':
                        $motif = "#^[5][0-9]{3}[ ]?[0-9]{4}[ ]?[0-9]{4}[ ]?[0-9]{4}$#";
                        break;
                    case 'amex':
                        $motif = "#^[13][0-9]{3}[ ]?[0-9]{4}[ ]?[0-9]{4}[ ]?[0-9]{4}$#";
                        break;
                }
            } else {
                $motif = "";
            }
        }

        //  ------------------------   Validation des champs simples   -------------------------------
        $tValidation = array();

        $tValidation = Utilitaires::validerChamp("modePaiement", "#^(paypal|carteCredit)$#", $tMessagesJson, $tValidation, true);
        if ($_POST['modePaiement'] == 'carteCredit') {
            $tValidation = Utilitaires::validerChamp("cartesCredit", "#^(visa|masterCard|amex)$#", $tMessagesJson, $tValidation, true);
            $tValidation = Utilitaires::validerChamp("nomCarte", "#^[a-zA-ZÀ-ÿ' -]+$#", $tMessagesJson, $tValidation, true);
            $tValidation = Utilitaires::validerChamp("numeroCarte", $motif, $tMessagesJson, $tValidation, true);
            $tValidation = Utilitaires::validerChamp("codeSecurite", "#^[0-9]{3}$#", $tMessagesJson, $tValidation, true);
            $tValidation = Utilitaires::validerChamp("moisCarte", "#^[0-9]{2}$#", $tMessagesJson, $tValidation, true);
            $tValidation = Utilitaires::validerChamp("anneeCarte", "#^[0-9]{4}$#", $tMessagesJson, $tValidation, true);
            $tValidation = Utilitaires::validerDateExpiration("moisCarte", "anneeCarte", $tMessagesJson, $tValidation);
        }

        $tValidation = Utilitaires::validerChamp("adresseLivraison", "#^on$#", $tMessagesJson, $tValidation, false);

        // --- Si «Utiliser l'adresse de livraison pour l'adresse de facturation» a été coché, les champs ne sont pas disponibles ---
        if ($this->arrLivraison['adresseFacturation']['valeur'] == '') {
            $tValidation = Utilitaires::validerChamp("nom", "#^[a-zA-ZÀ-ÿ' -]+$#", $tMessagesJson, $tValidation, true);
            $tValidation = Utilitaires::validerChamp("prenom", "#^[a-zA-ZÀ-ÿ' -]+$#", $tMessagesJson, $tValidation, true);
            $tValidation = Utilitaires::validerChamp("adresse", "#^[0-9]{1,6} [- 'a-zA-ZÀ-ÿ0-9]+$#", $tMessagesJson, $tValidation, true);
            $tValidation = Utilitaires::validerChamp("ville", "#^[a-zA-ZÀ-ÿ '-]+$#", $tMessagesJson, $tValidation, true);
            $tValidation = Utilitaires::validerChamp("province", "#^[A-Z]{2}$#", $tMessagesJson, $tValidation, true);
            $tValidation = Utilitaires::validerChamp("codePostal", "#^[A-Za-z][0-9][A-Za-z] [0-9][A-Za-z][0-9]$#", $tMessagesJson, $tValidation, true);
        }

        // -----  Vérifie s'il y a des champs invalides parmi le tableau créé à partir des données reçues -----
        $tChampsValides = array_column($tValidation, 'champValide');
        $invalide = in_array(false, $tChampsValides);


        // -----  S'il y a des champs invalides, on reste sur la page du formulaire  -----
        if ($invalide) {
            $this->session->setItem('arrFacturation', $tValidation);
            header('Location: index.php?controleur=facturation&action=afficher');
            exit;

        } // -----  Si tout est valide, on redirige vers la page de facturation  -----
        else {

            // -----  Si l'adresse de facturation équivaut à l'adresse de livraison, il faut l'ajouter en session avec les infos de la carte  -----
            if ($this->arrLivraison['adresseFacturation']['valeur'] == 'on') {
                $tValidation = array_merge($tValidation, $this->arrLivraison);
                $this->session->setItem('arrFacturation', $tValidation);
            } else {
                $this->session->setItem('arrFacturation', $tValidation);
            }

            header('Location: index.php?controleur=validation&action=afficher');
            exit;
        }
    }
}