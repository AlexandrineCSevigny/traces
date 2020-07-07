<?php
/**
 * @Project Traces - Courriel
 * @author Camille Dion-Bolduc <camille.dion.bolduc@gmail.com>
 * @date Septembre 2019 - Version 1.0.1
 */

declare(strict_types=1);

namespace App\Courriels;

// Pour utiliser les serveurs de Gmail
use App\App;
use PHPMailer\PHPMailer\PHPMailer;
use App\Modeles\Province;
use App\Modeles\Adresse;
use App\Modeles\Commande;
use App\Modeles\ModePaiement;

// Si il y a un problème dans le courriel
use PHPMailer\PHPMailer\Exception;


class Courriel
{
    private $courriel = null;
    private $blade = null;
    private $session = null;
    private $sessionPanier = null;

    public function __construct(string $unCourriel)
    {
        $this->blade = App::getInstance()->getBlade();
        $this->session = App::getInstance()->getSession();
        $this->sessionPanier = App::getInstance()->getSessionPanier();

        // On affiche les données de la page
        $arrClient = $this->session->getItem('client');
        $arrLivraison = $this->session->getItem('arrLivraison');
        $arrFacturation = $this->session->getItem('arrFacturation');
        $arrModeLivraison = $this->session->getItem('livraison');
        $infosPanier = $this->sessionPanier;
        $livres = $this->sessionPanier->getItems();

        // -------  Affichage du numéro de téléphone  ---------
        $indicatif = substr($arrClient->telephone, 0, 3);
        $troisChiffres = substr($arrClient->telephone, 4, 3);
        $quatreChiffres = substr($arrClient->telephone, 6, 4);
        $numeroTelFormate = '(' . $indicatif . ') ' . $troisChiffres . '-' . $quatreChiffres;

        // -------  Affichage de la province  ---------
        $provinceLivraison = Province::trouverProvince($arrLivraison['province']['valeur']);
        $provinceFacturation = Province::trouverProvince($arrFacturation['province']['valeur']);

        // -------  Affichage du numéro de carte  ---------
        if (isset($arrFacturation)) {
            if ($arrFacturation['modePaiement']['valeur'] == 'carteCredit') {
                $numeroCarteFormate = str_replace(' ', '', $arrFacturation['numeroCarte']['valeur']);
                $carteCache = str_replace(substr($numeroCarteFormate, 0, 12), 'XXXX XXXX XXXX ', $numeroCarteFormate);
            }
        }

        // Préparer le contenu HTML du courriel
        $tDonnees = array("panier" => $this->sessionPanier->getNombreTotalItems());
        $tDonnees = array_merge($tDonnees, array("arrClient" => $arrClient));
        $tDonnees = array_merge($tDonnees, array("arrLivraison" => $arrLivraison));
        $tDonnees = array_merge($tDonnees, array("arrFacturation" => $arrFacturation));
        $tDonnees = array_merge($tDonnees, array("arrModeLivraison" => $arrModeLivraison));
        $tDonnees = array_merge($tDonnees, array("numeroTelFormate" => $numeroTelFormate));
        $tDonnees = array_merge($tDonnees, array("provinceLivraison" => $provinceLivraison->nom));
        $tDonnees = array_merge($tDonnees, array("provinceFacturation" => $provinceFacturation->nom));
        $tDonnees = array_merge($tDonnees, array("infosPanier" => $infosPanier));
        $tDonnees = array_merge($tDonnees, array("livres" => $livres));

        if (isset($arrFacturation)) {
            if ($arrFacturation['modePaiement']['valeur'] == 'carteCredit') {
                $tDonnees = array_merge($tDonnees, array("carteCache" => $carteCache));
            }
        }

        $unContenuHTML = $this->blade->run("courriels.confirmation", $tDonnees);
        $unContenuHTML_enTexte = "Votre commande vous sera expédiée selon les modalités que vous avez choisies. 
        N\'hésitez pas à consulter notre service à la clientèle pour plus d\'informations relatives à votre commande ou votre compte. 
        Votre numéro de confirmation est le&nbsp;: 7463-4846-2245";

        // True indique que les exceptions seront lancées (Throwable) et non retourné en valeur retour de la méthode send
        $this->courriel = new PHPMailer(true);

        //Configuration du serveur d'envoi
        $this->courriel->SMTPDebug = 0;                   // Activer le débogage 0 = off, 1 = messages client, 2 = messages client et serveur
        $this->courriel->isSMTP();                         // Envoyer le courriel avec le protocole standard SMTP
        $this->courriel->Host = '';    // Adresse du serveur d'envoi SMTP
        $this->courriel->SMTPAuth = true;                // Activer l'authentification SMTP
        $this->courriel->Username = ''; // Nom d'utilisateur SMTP
        $this->courriel->Password = '';          // Mot de passe SMTP
        $this->courriel->SMTPSecure = 'TLS';               // Activer l'encryption TLS, `PHPMailer::ENCRYPTION_SMTPS` est aussi accepté
        $this->courriel->Port = 587;                 // Port TCP à utiliser pour la connexion SMTP

        // Configuration du courriel
        // Définir l'adresse de l'envoyeur.
        $expediteur = 'achat@traces.com';
        $this->courriel->setFrom($expediteur, "Achats - Traces");
        $this->courriel->From = $expediteur;

        // Ajouter l'adresse du destinataire (le nom est optionel)
        $this->courriel->addAddress($unCourriel); //Je dois ajouter l'adresse du destinataire
        $this->courriel->addReplyTo('info@traces.com', 'Information - Traces');

        // Contenu:
        $this->courriel->isHTML(true);  // Définir le type de contenu du courriel.
        $this->courriel->Subject = "Traces - Confirmation d'achat 7463-4846-2245";
        $this->courriel->Body = $unContenuHTML;
        $this->courriel->AltBody = $unContenuHTML_enTexte; // Si le client ne supporte pas le courriels HTML
    }

    public function envoyer(): string
    {
        try {
            // C'est ici que les exceptions peuvent arriver...
            // Si il a pas de catch, il va s'arrêter à send() et aura pas de return que ça a réussit!
            $this->courriel->send();
            return "";

            // Exception c'est un problème - exemple le mot de passe!
            // Objet créer et envoyer - mais s'il le CATCH
            // Le bloc try va s'arrêter, on va l'attraper et on va faire une action
        } catch (Exception $e) {
            // Gérer les exceptions spécifique à PHPMailer
            return "Le message ne peut pas être envoyé, erreur spécifique PHPMailer. ❌" . '<br /><br />' . $e;
        }

            // Avec SLASH
            // On va aller à la racine du language, donc à PHP on va aller voir la classe en tant que tel
            // Erreur PHP en tant que tel, PDO, erreur qu'on peut voir dans le navigateur
        catch (\Exeception $e) {
            // Gérer les exeptions internes de PHP
            return "Le message ne peut pas être envoyé, exception interne PHP. ❌" . '<br /><br />' . $e;
        }
    }
}
