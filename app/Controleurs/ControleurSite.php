<?php
/**
 * @Project Traces - ControleurSite
 * @author Camille Dion-Bolduc <camille.dion.bolduc@gmail.com>
 * @author Emie Pelletier <emiepelletier@hotmail.com>
 * @date Septembre 2019 - Version 1.0.1
 */
declare(strict_types=1);

namespace App\Controleurs;

use App\App;
use App\Modeles\Actualite;
use App\Modeles\Auteur;
use App\Modeles\Livre;
use App\Utilitaires;


class ControleurSite
{
    private $blade = null;
    private $messageSession = null;
    private $messageCookie = null;
    private $Utilitaires = null;
    private $id = '';
    private $sessionPanier = null;

    public function __construct()
    {
        $this->blade = App::getInstance()->getBlade();
        $this->session = App::getInstance()->getSession();
        $this->sessionPanier = App::getInstance()->getSessionPanier();
    }

    public function accueil(): void{
        // Session
        $session = App::getInstance()->getSession();

        // préférable de mettre la logique ici pour l'affichage de message et d'envoyer seulement les messages
        $tDonnees = array("nomPage" => "Accueil");

        // --- Ajoute à la session la page en cours pour la connexion ---
        $session->setItem('nomPage', 'accueil');

        // On enlève les préférences pour éviter un bug avec la querystring et le fil d'ariane
        if (isset($_SESSION['preferences'])){
            $this->session->supprimerItem('preferences'); }

        $tDonnees = array_merge($tDonnees, array("messageCookie" => $this->messageCookie));
        $tDonnees = array_merge($tDonnees, array("messageSession" => $this->messageSession));

        /*------- Affichage de la page d'acceuil ------*/

        // Trois Coup de coeur random
        $coupsdecoeur = Livre::trouverCoupDeCoeur();
        shuffle($coupsdecoeur);
        array_splice($coupsdecoeur, 3);
        $tDonnees = array_merge($tDonnees, array("coupsdecoeur" => $coupsdecoeur));

        //Deux nouveautées (Random)
        $nouveautes = Livre::trouverNouveautes();
        shuffle($nouveautes);
        array_splice($nouveautes, 2);
        $tDonnees = array_merge($tDonnees, array("nouveautes" => $nouveautes));

        // Deux actualités les plus récentes
        $actualites = Actualite::trouverActualites();
        $tDonnees = array_merge($tDonnees, array("actualites" => $actualites));

        // Nombre total d'objet dans le panier
        $tDonnees = array_merge($tDonnees, array("panier" => $this->sessionPanier->getNombreTotalItems()));

        echo $this->blade->run("accueil", $tDonnees);
    }

    public function apropos(): void {
        $tDonnees = array("nomPage" => "À propos");
        // Nombre total d'objet dans le panier
        $tDonnees = array_merge($tDonnees, array("panier" => $this->sessionPanier->getNombreTotalItems()));
        echo $this->blade->run("apropos", $tDonnees);
    }

    public function page404(): void{
        // Merge des données pour la page 404
        $tDonnees = array();
        // Nombre total d'objet dans le panier
        $tDonnees = array_merge($tDonnees, array("panier" => $this->sessionPanier->getNombreTotalItems()));
        echo $this->blade->run("erreur404", $tDonnees);
    }

    public function afficherResultats()
    {
        // Si «js» est dans la querystring, le bouton du formulaire a été cliqué
        // Le javascript a donc été désactivé, il s'agit d'une solution de repli
        if (isset($_GET['js'])) {

            $valeurListe = $_POST['sujet'];
            $valeurChamp = $_POST['recherche'];

            // -------  Validation valeur liste déroulante  --------
            if (isset($_POST['sujet']) == true) {
                $valeurListe = $_POST['sujet'];

                $patternListe = '#^[a-zA-Z]+$#';
                $listeValide = preg_match($patternListe, $valeurListe);
            }

            // -------  Validation valeur champ de recherche --------
            if (isset($_POST['recherche']) == true) {
                $valeurChamp = $_POST['recherche'];

                $patternChamp = '#^[a-zA-ZÀ-ÿ0-9-]+$#';
                $champValide = preg_match($patternChamp, $valeurChamp);
            }

            $resultats='';
            if ($listeValide == true && $champValide == true) {

                switch ($valeurListe) {
                    case 'auteur':
                        $resultats = Auteur::trouverParMotscle($valeurChamp);
                        break;
                    case 'isbn':
                        $resultats = Livre::trouverParMotscle($valeurChamp, $valeurListe);
                        break;
                    case 'sujet':
                        $resultats = Livre::trouverParMotscle($valeurChamp, 'mots_cles');
                        break;
                    case 'titre':
                        $resultats = Livre::trouverParMotscle($valeurChamp, $valeurListe);
                        break;
                }
            }

            $tDonnees = array('valeurListe' => $valeurListe);
            $tDonnees = array_merge($tDonnees, array("panier" => $this->sessionPanier->getNombreTotalItems()));
            $tDonnees = array_merge($tDonnees, array('valeurChamp' => $valeurChamp));
            $tDonnees = array_merge($tDonnees, array('valeurListe' => $valeurListe));

            if(isset($resultats)){
                $tDonnees = array_merge($tDonnees, array('resultats' => $resultats));
            }

            echo $this->blade->run("resultatsRecherche", $tDonnees);

        } else {

            $valeurListe = $_GET['valeurListe'];
            $valeurChamp = $_GET['valeurChamp'];

            // -------  Validation valeur liste déroulante  --------
            if (isset($_GET['valeurListe']) == true) {
                $valeurListe = $_GET['valeurListe'];

                $patternListe = '#^[a-zA-Z]+$#';
                $listeValide = preg_match($patternListe, $valeurListe);
            }

            // -------  Validation valeur champ de recherche --------
            if (isset($_GET['valeurChamp']) == true) {
                $valeurChamp = $_GET['valeurChamp'];

                $patternChamp = '#^[a-zA-ZÀ-ÿ0-9- ]+$#';
                $champValide = preg_match($patternChamp, $valeurChamp);
            }

            $liste = '';

            if ($listeValide == true && $champValide == true) {

                switch ($valeurListe) {
                    case 'auteur':
                        $auteurs = Auteur::trouverParMotscle($valeurChamp);

                        // ---  Met en tableau les données à afficher  ---
                        $resultats = array();
                        $cpt = 0;
                        foreach ($auteurs as $auteur) {
                            $resultats[$cpt] = $auteur->prenom . " " . $auteur->nom;
                            $cpt++;
                        }
                        break;

                    case 'isbn':
                        $livres = Livre::trouverParMotscle($valeurChamp, $valeurListe);

                        // ---  Met en tableau les données à afficher  ---
                        $resultats = array();
                        $cpt = 0;
                        foreach ($livres as $livre) {
                            $resultats[$cpt] = $livre->isbn;
                            $cpt++;
                        }
                        break;

                    case 'sujet':
                        $livres = Livre::trouverParMotscle($valeurChamp, 'mots_cles');

                        // ---  Met en tableau les données à afficher  ---
                        $resultats = array();
                        $cpt = 0;
                        foreach ($livres as $livre) {
                            $resultats[$cpt] = $livre->mots_cles;
                            $cpt++;
                        }
                        break;

                    case 'titre':
                        $livres = Livre::trouverParMotscle($valeurChamp, $valeurListe);

                        // ---  Met en tableau les données à afficher  ---
                        $resultats = array();
                        $cpt = 0;
                        foreach ($livres as $livre) {
                            $resultats[$cpt] = $livre->formaterTitre();
                            $cpt++;
                        }
                        break;
                }

                // -----  Met en string le tableau pour l'affichage en liste  -----
                for ($cpt = 0; $cpt < count($resultats); $cpt++) {
                    $liste = $liste . '<li><a href="#" class="recherche__aideSaisieLien">' . $resultats[$cpt] . '</a></li>';
                }
            }

            echo $liste;
        }
    }
}

