<?php
/**
 * @Project Traces - ControleurLivre
 * @author Alexandrine C. Sevigny <asevigny@outlook.fr>
 * @author Emie Pelletier <emiepelletier@hotmail.com>
 * @date Septembre 2019 - Version 1.0.1
 */
declare(strict_types=1);

namespace App\Controleurs;

use App\FilAriane;
use App\Modeles\Categorie;
use App\Modeles\Livre;
use App\Session;
use Utilitaires;
use App\App;

class ControleurLivre
{
    private $blade = null;
    private $session = null;
    private $sessionPanier;
    private $preferences = array();

    public function __construct()
    {
        $this->blade = App::getInstance()->getBlade();
        $this->session = App::getInstance()->getSession();
        $this->sessionPanier = App::getInstance()->getSessionPanier();
    }

    public function index(): void
    {
        // -----------------------------   Récupération de données querystring   -------------------------------

        // -------- Page en cours ---------

        if (isset($_GET['page']) == true) {
            $numeroPage = $_GET['page'];
        } else {
            $numeroPage = 0;
        }

        // enregistre dans le tableau préférence le numéro de la page
        $this->preferences['numeroPage'] = $numeroPage;

        // ------- Catégorie en cours -------

        if (isset($_GET['categorie']) == true) {
            $categorie = $_GET['categorie'];

            $patternCategorieEnCours = '#^([0-9]?[0-9]{1})$#'; //maximum 99 catégories
            $categorieEnCoursValide = preg_match($patternCategorieEnCours, $categorie);

            if ($categorieEnCoursValide == true) {
                $categorieEnCours = Categorie::trouver($categorie);
            }
        }

        // ----------------   Pagination (en fonction de la catégorie sélectionnée ou non)  ----------------

        // ------  Nombre de livres maximum par page  ------
        if (isset($_POST['quantite']) == true) {
            $nbreChoisi = $_POST['quantite'];

            $patternTri = '#^(0|[0-9]{2})$#';
            $nbreValide = preg_match($patternTri, $nbreChoisi);

            if ($nbreValide == true) {
                $nbreLivreMaxParPage = (int)$nbreChoisi;

                $this->preferences['quantite'] = $nbreLivreMaxParPage;

                // ----- Cas particulier: Tout voir -----

                if ($nbreLivreMaxParPage == 0) {
                    $nbreLivreMaxParPage = (int)481;

                    $this->preferences['quantite'] = $nbreLivreMaxParPage;
                }
            }

        } else if (isset($_POST['quantite']) == false) {

            // -------  Maintient le choix de quantité en session pour chaque page ------

            if (isset($_SESSION['quantite']) == true) {
                $nbreLivreMaxParPage = $_SESSION['preferences']['quantite'];

                $this->preferences['quantite'] = $nbreLivreMaxParPage;

            } else {

                // ------  Aucune quantité choisi, on met par défaut 12------
                $nbreLivreMaxParPage = (int)12;

                $this->preferences['quantite'] = $nbreLivreMaxParPage;
            }

        }

        // page courante
        $indexCourant = $numeroPage * $nbreLivreMaxParPage;

        // -----------------------   Tris - Met en post et en session le tri choisi   --------------------------

       if (isset($_POST['tri']) == true) {

            $triChoisi = $_POST['tri'];

            $patternTri = '#^([a-z]-[a-z]||[$]{1,3}-[$]{1,3})$#';
            $triValide = preg_match($patternTri, $triChoisi);

            if ($triValide == true) {

                switch ($triChoisi) {
                    case 'a-z':
                        $tri = 'titre';
                        $ordre = 'ASC';
                        break;
                    case 'z-a':
                        $tri = 'titre';
                        $ordre = 'DESC';
                        break;
                    case '$-$$$':
                        $tri = 'prix';
                        $ordre = 'ASC';
                        break;
                    case '$$$-$':
                        $tri = 'prix';
                        $ordre = 'DESC';
                        break;
                    default:
                        $tri = 'titre';
                        $ordre = 'ASC';
                }

                $this->preferences['tri'] = $tri;
                $this->preferences['ordre'] = $ordre;

                $this->session->setItem('preferences', $this->preferences);
            }

        } else if (isset($_POST['tri']) == false) {

            // -------  Maintient le choix des tris en session pour chaque page ------

            if (isset($_SESSION['preferences']['tri']) && isset($_SESSION['preferences']['ordre'])) {

                $tri = $_SESSION['preferences']['tri'];
                $ordre = $_SESSION['preferences']['ordre'];

                $this->preferences['tri'] = $tri;
                $this->preferences['ordre'] = $ordre;

                $this->session->setItem('preferences', $this->preferences);
            } else {

                // ------  Aucun tri choisi, on met par défaut le tri de A à Z ------

                $tri = 'titre';
                $ordre = 'ASC';

                $this->preferences['tri'] = $tri;
                $this->preferences['ordre'] = $ordre;

                $this->session->setItem('preferences', $this->preferences);
            }
        }

        // ------------------------------------   Livres à afficher   ----------------------------------------

        // ----- Livres filtrés par catégorie -----
        if (isset($_GET['categorie']) == true) {

            $livres = Livre::trouverParCategorie($_GET['categorie'], $indexCourant, $nbreLivreMaxParPage, $tri, $ordre);

            // url pagination
            $urlPagination = 'index.php?controleur=livre&action=index&categorie=' . $_GET['categorie'];

            // nombre total de livres
            $nbreTotalLivre = Livre::compterFiltre($_GET['categorie']);

            // enregistre dans le tableau préférence le id de la catégorie
            $this->preferences['categorie'] = $_GET['categorie'];

            // ----- Cas particulier: Liste déroulante en mobile catégorie choisi = tous les livres -----

            if ($_GET['categorie'] == 'catalogue') {

                // enregistre dans le tableau préférence le type de la catégorie
                $this->preferences['categorie'] = 'catalogue';

                // Rediriger vers la page du catalogue avec tous les livres
                header('Location: index.php?controleur=livre&action=index');
                exit;
            }

        } // ----- Livres filtrés par nouveauté -----
        else if (isset($_GET['nouveau']) == true) {

            $livres = Livre::trouverParNouveaute($indexCourant, $nbreLivreMaxParPage, $tri, $ordre);

            // url pagination
            $urlPagination = 'index.php?controleur=livre&action=index&nouveau=nouveau';

            // nombre total de livres
            $nbreTotalLivre = Livre::compterNouveautes();

            // enregistre dans le tableau préférence le type de la catégorie
            $this->preferences['categorie'] = 'nouveau';


        } else {
            // ----- Affichage de tous les livres (par défaut) -----

            $livres = Livre::trouverParLimite($indexCourant, $nbreLivreMaxParPage, $tri, $ordre);

            // url pagination
            $urlPagination = 'index.php?controleur=livre&action=index';

            // nombre total de livres
            $nbreTotalLivre = Livre::compter();

            // enregistre dans le tableau préférence le type de la catégorie
            $this->preferences['categorie'] = 'catalogue';
        }

        // Enregistre dans la session le tableau des préférences
        $this->session->setItem('preferences', $this->preferences);

        // nombre total de pages
        $nbreTotalPages = ceil($nbreTotalLivre / $nbreLivreMaxParPage) - 1;


        // --------------  Récupérer le fil d'Ariane  --------------

        $filAriane = FilAriane::majFilArianne();

        // --------------  Catégories  ----------------

        $categories = Categorie::trouverCategories();

        // --- Ajoute à la session la page en cours pour la connexion ---

        $this->session->setItem('nomPage', 'catalogue');

        // --------------  Infos pour la vue blade  ----------------

        $tDonnees = array("nomPageAccueil" => "Accueil");
        $tDonnees = array_merge($tDonnees, array("livres" => $livres));
        $tDonnees = array_merge($tDonnees, array("nomPage" => "Catalogue"));
        $tDonnees = array_merge($tDonnees, array("numeroPage" => $numeroPage));
        $tDonnees = array_merge($tDonnees, array("nombreTotalPages" => $nbreTotalPages));
        $tDonnees = array_merge($tDonnees, array("urlPagination" => $urlPagination));
        $tDonnees = array_merge($tDonnees, array("nombreLivresFiltres" => $nbreTotalLivre));
        $tDonnees = array_merge($tDonnees, array("nombreTotalLivres" => Livre::compter()));
        $tDonnees = array_merge($tDonnees, array("categories" => $categories));
        $tDonnees = array_merge($tDonnees, array("filAriane" => $filAriane));
        $tDonnees = array_merge($tDonnees, array("panier" => $this->sessionPanier->getNombreTotalItems()));

        if (isset($categorieEnCours) == true) {
            $tDonnees = array_merge($tDonnees, array("categorieEnCours" => $categorieEnCours));
        }
        echo $this->blade->run("livres.index", $tDonnees);
    }

    // Affichage de la fiche du livre
    public function fiche(): void
    {
        $pattern = '#^[0123]-[0-9]{1,7}-[0-9]{1,}-[0-9|a-z]$#i';
        $livre = null;

        // Récupérer la querystring
        if (isset($_GET['isbn'])) {

            // Validation de l'isbn
            $isbn = -1;
            $pregMatch = preg_match($pattern, $_GET['isbn']);
            if ($pregMatch == 1) {
                $isbn = $_GET['isbn'];
                $livre = Livre::trouverParIsbn($isbn);
            } else {
                header('Location: index.php?controleur=site&action=page404');
                exit;
            }
        }

        // Récupérer le fil d'Ariane
        $filAriane = FilAriane::majFilArianne();

        if ($livre !== null) {
            $nbTotal = $this->sessionPanier->getNombreTotalItemsDifferents();
            $sousTotal = $this->sessionPanier->formaterPrix($this->sessionPanier->getMontantSousTotal());

            $tDonnees = array("livre" => $livre);
            $tDonnees = array_merge($tDonnees, array("nbTotal" => $nbTotal));
            $tDonnees = array_merge($tDonnees, array("panier" => $this->sessionPanier->getNombreTotalItems()));
            $tDonnees = array_merge($tDonnees, array("sousTotal" => $sousTotal));
            $tDonnees = array_merge($tDonnees, array("filAriane" => $filAriane));

            if (isset($_SESSION['message'])) {
                // Récupérer le message
                $message = $this->sessionPanier->getMessage();
                // Supprimer le message
                $this->sessionPanier->supprimerMessage();
                $tDonnees = array_merge($tDonnees, array("message" => $message));
            }
            echo $this->blade->run("livres.fiche", $tDonnees);
        } else {
            header('Location: index.php?controleur=site&action=page404');
            exit;
        }
    }
}