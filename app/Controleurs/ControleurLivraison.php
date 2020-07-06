<?php
/**
 * @Project Traces - ControleurLivraison
 * @author Emie Pelletier <emiepelletier@hotmail.com>
 * @date Novembre 2019 - Version 1.0.1
 */

namespace App\Controleurs;

use App\App;
use App\Modeles\Adresse;
use App\Modeles\Client;
use App\Utilitaires;

class ControleurLivraison
{
    private $blade = null;
    private $session = null;
    private $sessionPanier = null;
    private $client = null;

    public function __construct()
    {
        $this->blade = App::getInstance()->getBlade();
        $this->session = App::getInstance()->getSession();
        $this->sessionPanier = App::getInstance()->getSessionPanier();
        $this->client = $this->session->getItem('client');
    }

    public function afficher(): void
    {
        // --- Vérifie si le client est connecté ---
        $clientConnecte = Utilitaires::verifierConnexionClient();

        if ($clientConnecte == true) {

            // --- Vérifie si le client est nouveau ---
            $adresseClient = Client::getAdresse($this->client->id_client);


            if ($adresseClient == null){

                // --- Récupération des données nouvellement entrées dans les champs ---
                $arrLivraison = $this->session->getItem('arrLivraison');

                // --- Suppression des données qui ont été stocké, utile pour une requête seulement ---
                $this->session->supprimerItem('arrLivraison');

                // --- Ajoute à la session la page en cours pour la connexion ---
                $this->session->setItem('nomPage', 'livraison');

                // --- L'adresse est mis à 2 pour la prochaine fois pour la complétition des champs
                $this->client->id_adresse_facturation = '2';
                $this->session->setItem('client', $this->client);

                $tDonnees = array("arrLivraison" => $arrLivraison);
                $tDonnees = array_merge($tDonnees, array("panier" => $this->sessionPanier->getNombreTotalItems()));
                echo $this->blade->run("transaction.livraison", $tDonnees);

            }
            else {
                // --- Récupérer les informations de l'adresse de livraison en fonction du client ---
                    
                $tDonnees = array("adresseClient" => $adresseClient);
                $tDonnees = array_merge($tDonnees, array("panier" => $this->sessionPanier->getNombreTotalItems()));
                echo $this->blade->run("transaction.livraison", $tDonnees);

                $this->session->setItem('arrLivraison', $adresseClient);
            }
        } else {

            // --- Ajoute à la session la page en cours pour la connexion ---
            $this->session->setItem('nomPage', 'livraison');

            // --- Redirection vers la page de connexion ---
            header('Location: index.php?controleur=client&action=afficher');
            exit;
        }
    }

    public function valider(): void
    {
        //  --------------   Récupération du contenu des messages en format JSON   -------------------
        $contenuFichierJson = file_get_contents("../ressources/lang/fr_CA.UTF-8/messagesValidation.json");
        $tMessagesJson = json_decode($contenuFichierJson, true);

        //  ------------------------   Validation des champs simples   -------------------------------
        $tValidation = array();
        $tValidation = Utilitaires::validerChamp("nom", "#^[a-zA-ZÀ-ÿ '-]+$#", $tMessagesJson, $tValidation, true);
        $tValidation = Utilitaires::validerChamp("prenom", "#^[a-zA-ZÀ-ÿ' -]+$#", $tMessagesJson, $tValidation, true);
        $tValidation = Utilitaires::validerChamp("adresse", "#^[0-9]{1,6} [- 'a-zA-ZÀ-ÿ0-9]+$#", $tMessagesJson, $tValidation, true);
        $tValidation = Utilitaires::validerChamp("ville", "#^[a-zA-ZÀ-ÿ '-]+$#", $tMessagesJson, $tValidation, true);
        $tValidation = Utilitaires::validerChamp("province", "#^[A-Z]{2}$#", $tMessagesJson, $tValidation, true);
        $tValidation = Utilitaires::validerChamp("codePostal", "#^[A-Za-z][0-9][A-Za-z] [0-9][A-Za-z][0-9]$#", $tMessagesJson, $tValidation, true);
        $tValidation = Utilitaires::validerChamp("adresseLivraison", "#^on$#", $tMessagesJson, $tValidation, false);
        $tValidation = Utilitaires::validerChamp("adresseFacturation", "#^on$#", $tMessagesJson, $tValidation, false);


        // -----  Vérifie s'il y a des champs invalides parmi le tableau créé à partir des données reçues -----
        $tChampsValides = array_column($tValidation, 'champValide');
        $invalide = in_array(false, $tChampsValides);


        // -----  S'il y a des champs invalides, on reste sur la page du formulaire  -----
        if ($invalide) {
            $this->session->setItem('arrLivraison', $tValidation);
            header('Location: index.php?controleur=livraison&action=afficher');
            exit;
        }
        // -----  Si tout est valide, on redirige vers la page de facturation  -----
        else {

            $this->session->setItem('arrLivraison', $tValidation);
            header('Location: index.php?controleur=facturation&action=afficher');
            exit;
        }
    }
}