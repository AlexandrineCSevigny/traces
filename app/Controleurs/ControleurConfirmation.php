<?php
/**
 * @author Camille Dion-Bolduc
 * @version 1.0.1
 */

namespace App\Controleurs;

use App\App;
use App\Courriels\Courriel;
use App\Utilitaires;
use App\Modeles\Province;

class ControleurConfirmation
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

        // On vérifie si le client existe (Sinon, on envoit à l'accueil)
        if (isset($_SESSION['client']) && isset($_SESSION['arrLivraison']) && isset($_SESSION['arrFacturation']) && isset($_SESSION['livraison'])) {

            // On affiche les données de la page
            $arrClient = $this->session->getItem('client');
            $arrLivraison = $this->session->getItem('arrLivraison');
            $arrFacturation = $this->session->getItem('arrFacturation');
            $arrModeLivraison = $this->session->getItem('livraison');

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

            // C'est ici que je vais appeller courrielConfirmation pour envoyer la page en confirmation
            $courriel = new Courriel($arrClient->courriel);
            $messageCourriel = $courriel->envoyer();
            echo $messageCourriel;

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

            $tDonnees = array("panier" => $this->sessionPanier->getNombreTotalItems());
            $tDonnees = array_merge($tDonnees, array("arrClient" => $arrClient));
            $tDonnees = array_merge($tDonnees, array("arrLivraison" => $arrLivraison));
            $tDonnees = array_merge($tDonnees, array("arrFacturation" => $arrFacturation));
            $tDonnees = array_merge($tDonnees, array("arrModeLivraison" => $arrModeLivraison));
            $tDonnees = array_merge($tDonnees, array("numeroTelFormate" => $numeroTelFormate));
            $tDonnees = array_merge($tDonnees, array("provinceLivraison" => $provinceLivraison->nom));
            $tDonnees = array_merge($tDonnees, array("provinceFacturation" => $provinceFacturation->nom));
            $tDonnees = array_merge($tDonnees, array("nbTotal" => "$nbTotal"));
            $tDonnees = array_merge($tDonnees, array("sousTotal" => "$sousTotal"));
            $tDonnees = array_merge($tDonnees, array("tps" => "$tps"));
            $tDonnees = array_merge($tDonnees, array("livraison" => "$livraison"));
            $tDonnees = array_merge($tDonnees, array("delai" => $dateEstimee));
            $tDonnees = array_merge($tDonnees, array("total" => "$total"));
            $tDonnees = array_merge($tDonnees, array("livres" => $livres));
            $tDonnees = array_merge($tDonnees, array("panier" => $this->sessionPanier->getNombreTotalItems()));

            if ($arrFacturation['modePaiement']['valeur'] == 'carteCredit') {
                $tDonnees = array_merge($tDonnees, array("carteCache" => $carteCache));
            }

            // On affiche la page avec les informations prises
            echo $this->blade->run("transaction.confirmation", $tDonnees);

            // Une fois que c'est affiché, on supprime les éléments de la session et la sessionPanier
            $this->session->supprimerItem('arrClient');
            $this->session->supprimerItem('arrLivraison');
            $this->session->supprimerItem('arrFacturation');
            $this->session->supprimerItem('arrlivraison');
            $this->sessionPanier->supprimer();
        } else {

            // Si le client n'existre plus ou pas (On a fait un refresh de page, etc...) on renvoit à l'accueil
            header('Location: index.php?controleur=site&action=accueil');
            exit;
        }
    }
}
