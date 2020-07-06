<?php
/**
 * @author Camille Dion-Bolduc (Griserie - Mandat A)
 * @version 1.0.4
 */

namespace App\Controleurs;

use App\App;
use App\Modeles\Client;
use App\Utilitaires;
use Panier;

class ControleurClient {

    private $tMessageJSON = null;

    public function __construct() {
        $this->blade = App::getInstance()->getBlade();
        $this->session = App::getInstance()->getSession();
        $this->sessionPanier = App::getInstance()->getSessionPanier();

        // Récupérer le contenu du JSON (Pour les messages d'erreurs)
        $contenuBruteJSON = file_get_contents("../ressources/lang/fr_CA.UTF-8/messagesValidation.json");
        $this->tMessageJSON = json_decode($contenuBruteJSON, true); }

    /*
    * @method afficher (Client connexion)
    * @desc Assemble la vue avec tClient
    */
    public function afficher(): void {
        $arrClient = null;
        if ($arrClient === null && isset($_SESSION["arrClient"])) {
            $arrClient = $this->session->getItem('arrClient');
            $this->session->supprimerItem('arrClient'); }

        // On va chercher le nom de la page pour la redirection spécifique
        if ($_SESSION != null) {
            $nomPage = $_SESSION['nomPage']; }

        // Assembler la vue
        $tDonnees = array("arrClient" => $arrClient);
        $tDonnees = array_merge($tDonnees, array("panier" => $this->sessionPanier->getNombreTotalItems()));
        echo $this->blade->run("client.formulaireConnexion", $tDonnees);
    }

    /*
    * @method creer (Nouveau client)
    * @desc Assemble la vue avec tValidation
    */
    public function creer(): void {
        $arrClient = null;
        // Si mon tableau arrClient est pas null, c'est que j'arrive de ma validation
        // et que j'ai des données à mettre dans le formulaire (et à corriger)
        if ($arrClient === null && isset($_SESSION["arrClient"])) {
            $arrClient = $this->session->getItem('arrClient');
            $this->session->supprimerItem('arrClient'); }

        // On va chercher le nom de la page pour la redirection spécifique
        if ($_SESSION != null) {
            $nomPage = $_SESSION['nomPage']; }

        // Assembler la vue
        $tDonnees = array("arrClient" => $arrClient);
        $tDonnees = array_merge($tDonnees, array("panier" => $this->sessionPanier->getNombreTotalItems()));
        echo $this->blade->run("client.formulaireCreer", $tDonnees);
    }

    /*
    * @method inserer (Nouveau client)
    * @desc Valide et créer un nouvel utilisateur dans la database
    */
    public function inserer(): void {
        // Récupération du contenu des messages en format JSON
        $contenuFichierJson = file_get_contents("../ressources/lang/fr_CA.UTF-8/messagesValidation.json");
        $tMessagesJson = json_decode($contenuFichierJson, true);

        // J'appelle la fonction validerChamp dans Utilitaires pour valider chacun de mes champs
        $arrClient = [];
        $arrClient = Utilitaires::validerChamp('courriel', "#^[a-zA-Z0-9][a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*[@][a-zA-Z0-9_ ]+([.][a-zA-Z0-9_-]+)*[.][a-zA-Z]{2,}$#", $tMessagesJson, $arrClient, true);
        $arrClient = Utilitaires::validerChamp('mot_de_passe', "#^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).[a-zA-Z0-9-_ ]{8,}$#", $tMessagesJson, $arrClient, true);
        $arrClient = Utilitaires::validerChamp('telephone', "#^[0-9]{10}$#", $tMessagesJson, $arrClient, true);
        $arrClient = Utilitaires::validerChamp('nom', "#^[a-zA-ZÀ-ÿ' -]+$#", $tMessagesJson, $arrClient, true);
        $arrClient = Utilitaires::validerChamp('prenom', "#^[a-zA-ZÀ-ÿ' -]+$#", $tMessagesJson, $arrClient, true);

        // -----  Vérifie s'il y a des champs invalides parmi le tableau créé à partir des données reçues -----
        $tChampsValides = array_column($arrClient, 'champValide');
        $invalide = in_array(false, $tChampsValides);

        // On vérifie si le courriel est déjà dans la BD (Donc, un client existant)
        if ($arrClient['courriel'] != null) {
            if ($arrClient['courriel']['champValide'] == 1) {
                $clientExistant = Client::trouverParCourriel($arrClient['courriel']['valeur']);
                if ($clientExistant == null) {
                    //Pas de client trouvé dans la BD
                } else {
                    if ($clientExistant == true) {
                       $arrClient['courriel'] = ['valeur' => '', 'valide' => 'faux', 'message' => $this->tMessageJSON{"connexion"}{"existant"}]; }
                }
            }
        }

        // -----  S'il y a des champs invalides, on reste sur la page de créer un compte  -----
        if ($invalide == true) {
            if (isset($_SESSION["arrClient"])) {
            } else { $this->session->setItem('arrClient', $arrClient); }

            // On va recréer le formulaire avec les informations à corriger
            header('Location: index.php?controleur=client&action=creer');
            exit;
        }

        else {
            // Si tout est valide :
            // On ajoute le nouveau Client dans la database
            // on redirige vers la page précédente (Transaction, ect...)
            $client = new Client();

            // Stocker dans des variables les valeurs du client validés
            $NCprenom = $arrClient['prenom']['valeur'];
            $NCnom = $arrClient['nom']['valeur'];
            $NCcourriel = $arrClient['courriel']['valeur'];
            $NCtelephone = $arrClient['telephone']['valeur'];
            $NCmot_de_passe = password_hash($arrClient['mot_de_passe']['valeur'], PASSWORD_DEFAULT);

            // On les ajouter à la DB (avec envoi d'arguments), on les enlève de la session
            $client->insererNouveauClient($NCcourriel, $NCmot_de_passe, $NCtelephone, $NCnom, $NCprenom);

            if (isset($_SESSION["arrClient"])) {
                $this->session->supprimer(); }

            // On regénère les id, question de sécurité
            $this->session->regenererId();
            $this->session->setItem('client', $client->id);

            // Tout est bon, on peut rediriger vers connexion avec un message de rétroaction pour dire qu'on peut se connecter !
            $arrClient['client']['message'] = "Vous pouvez maintenant vous connecter ! 
            Veuillez entrer votre mot de passe pour compléter la connexion et accéder à votre profil.";

            // On recréer le formulaire de connexion
            $tDonnees = array("arrClient" => $arrClient);
            $tDonnees = array_merge($tDonnees, array("panier" => $this->sessionPanier->getNombreTotalItems()));
            echo $this->blade->run("client.formulaireConnexion", $tDonnees);
        }
    }

    /*
    * @method valider (Connexion client)
    * @desc Valide et revoit à la bonne page selon l'arrivée
    */
    public function valider(): void {
        // Récupération du contenu des messages en format JSON
        $contenuFichierJson = file_get_contents("../ressources/lang/fr_CA.UTF-8/messagesValidation.json");
        $tMessagesJson = json_decode($contenuFichierJson, true);
        $client = null;

        $arrClient = [];
        $arrClient = Utilitaires::validerChamp('courriel', "#^[a-zA-Z0-9][a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*[@][a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*[.][a-zA-Z]{2,}$#", $tMessagesJson, $arrClient, true);
        $arrClient = Utilitaires::validerChamp('mot_de_passe', "#^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).[a-zA-Z0-9-_ ]{8,}$#", $tMessagesJson, $arrClient, true);

        // -----  Vérifie s'il y a des champs invalides parmi le tableau créé à partir des données reçues ----- //
        $tChampsValides = array_column($arrClient, 'champValide');
        $invalide = in_array(false, $tChampsValides);

        // Si le courriel est valide :
        if ($arrClient['courriel']['champValide'] === true){
            // Est-ce que le courriel est associé à un compte?
            $client = Client::trouverParCourriel($arrClient['courriel']['valeur']);

            // Si un compte est associé (Donc, pas null), on peut comparer le mot de passe avec le client
            if($client !=null) {

                // On s'assure que le mot de passe est bien valide
                if ($arrClient['mot_de_passe']['champValide'] === true) {
                    if (password_verify($arrClient['mot_de_passe']['valeur'], $client->mot_de_passe)) {

                    // Je stock dans la session le nom de mon utilisateur pour l'afficher
                    // Je dis aussi à ma session que je suis connectée et je pourrai l'afficher dans le header
                    $this->session->setItem('client', $client);
                    $this->session->setItem('connecte', 'true');

                    // Si le mot de passe est correct, on peut se connecter (Grand switch selon nomPage)
                    $nomPage = $_SESSION['nomPage'];
                    switch($nomPage){
                        case "accueil" :
                            header('Location: index.php?controleur=site&action=accueil');
                            exit;
                            break;

                        case "catalogue" :
                            header('Location: index.php?controleur=livre&action=index');
                            exit;
                            break;

                        case "panier" :
                            header('Location: index.php?controleur=panier&action=fiche');
                            exit;
                            break;

                        case "livraison" :
                            header('Location: index.php?controleur=livraison&action=afficher');
                            exit;
                            break;

                        case "facturation" :
                            header('Location: index.php?controleur=facturation&action=afficher');
                            exit;
                            break;

                        case "validation" :
                            header('Location: index.php?controleur=validation&action=afficher');
                            exit;
                            break;

                        default :
                            header('Location: index.php?controleur=site&action=accueil');
                            exit;
                            break;
                        }
                    } else {
                        // Sinon, c'est une faute dans le mot de passe du compte
                        $arrClient['mot_de_passe'] = ['valeur'=> $arrClient['mot_de_passe']['valeur'], 'champValide' => false, 'message' => $this->tMessageJSON{"connexion"}{"mdpClientIncorrect"}];
                    }
                } else {
                    // Mot de passe incorrect
                    $arrClient['mot_de_passe'] = ['valeur'=> $arrClient['mot_de_passe']['valeur'], 'champValide' => false, 'message' => $this->tMessageJSON{"connexion"}{"mdpIncorrect"}];
                }
            } else {
                // Sinon, le courriel est innexistant
                $arrClient['courriel'] = ['valeur'=> $arrClient['courriel']['valeur'], 'champValide' => false, 'message' => $this->tMessageJSON{"connexion"}{"courrielInconnu"}];
            }
        }

//        // Si on a pas passé dans la connexion, c'est qu'on est pas connecté et qu'on a des erreurs
//        $this->session->setItem('connecte', 'false');

        // On doit donc redigirer vers la connexion
        if (isset($_SESSION["arrClient"])) {
        } else { $this->session->setItem('arrClient', $arrClient); }

        // On va recréer le formulaire avec les informations à corriger
        $tDonnees = array("arrClient" => $arrClient);
        $tDonnees = array_merge($tDonnees, array("panier" => $this->sessionPanier->getNombreTotalItems()));
        echo $this->blade->run("client.formulaireConnexion", $tDonnees);
    }

    /*
    * @method deconnecter
    * @desc Enlève le client de la session (et son état connecté enregistré pour le header) de la session et redirige à l'accueil
    */
    public function deconnecter(): void {
        // On retire le client et la connexion de la session
        // On regénère le id pour la sécurité
        $this->session->supprimerItem('arrClient');
        $this->session->supprimerItem('client');
        $this->session->supprimerItem('connecte');
        $this->session->regenererId();

        header('Location: index.php?controleur=site&action=accueil');
        exit;
    }

    /*
    * @method courrielDisponibilite
    * @desc Est appelé par le Ajax, reçoit un courriel par query string,
    * passe son contenu dans un preg_match et ensuite appelle ma function de trouver un client par courriel
    * @return int $client = 1 : S'il s'agit d'un courriel non-disponible, déjà dans la base de donnée
    * @return int $client = 0 : S'il s'agit d'un courriel disponible ou pas d'un courriel
    */
    public function courrielDisponibilite() {
        // On initialise les variables & pattern utilisés
        $client = null;
        $champCourriel = "";
        $courrielDisponible = 0;
        $pattern = '#^[a-zA-Z0-9][a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*[@][a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*[.][a-zA-Z]{2,}$#';

        // Si il y a quelque chose dans ma querystring, on le stock et le passe dans le pregMatch
        if (isset($_GET['courriel'])) {
            $champCourriel = $_GET['courriel'];
            $pregMatch = preg_match($pattern, $champCourriel);

            // S'il s'agit d'un courriel validé, on peut continuer avec la requête
            if ($pregMatch == 1) {
                // On stock le résultat de trouverParCourriel dans $client
                $client = Client::trouverParCourriel($champCourriel);
                if ($client == null) {
                    // $client == null : Courriel non utilisé ou pas un courriel valide
                    $courrielDisponible = 0;
                }
                else {
                    // $client retourne autre chose que null, donc un client existant !
                    $courrielDisponible = 1;
                }
            }
        }
        // Retourne 0 : S'il s'agit pas d'un courriel, ou d'un courriel disponible
        // Retourne 1 : S'il s'agit d'un courriel non-disponible, déjà dans la base de donnée
        echo $courrielDisponible;
    }
}