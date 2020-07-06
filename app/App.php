<?php
/**
 * @Project Traces - App
 * @author Camille Dion-Bolduc <camille.dion.bolduc@gmail.com>
 * @author Alexandrine C. Sevigny <asevigny@outlook.fr>
 * @author Emie Pelletier <emiepelletier@hotmail.com>
 * @date Septembre 2019 - Version 1.0.1
 */
declare(strict_types=1);

namespace App;

use App\Controleurs\ControleurFacturation;
use App\Controleurs\ControleurLivre;
use App\Controleurs\ControleurClient;
use App\Controleurs\ControleurConfirmation;
use App\Controleurs\ControleurPanier;
use App\Controleurs\ControleurSite;
use App\Controleurs\ControleurLivraison;
use App\Controleurs\ControleurValidation;
//use App\Courriels\Courriel;
use App\Session\SessionPanier;
use PDO;
use eftec\bladeone\BladeOne;


class App
{
    private static $instance = null;
    private $pdo = null;
    private $blade = null;
    private $cookie = null;
    private $session = null;
    private $sessionPanier = null;

    private function __construct()
    {
    }

    public static function getInstance(): App
    {
        if (App::$instance === null) {
            App::$instance = new App();
        }
        return App::$instance;
    }

    public function demarrer(): void
    {
        $this->getSession()->demarrer();
        $this->configurerEnvironnement();
        $this->routerLaRequete();
        $this->getPDO();

        // $courriel = new Courriel;
    }

    public function getSession(): Session
    {
        if ($this->session === null) {
            $this->session = new Session();
        }
        return $this->session;
    }

    public function getCookie(): Cookie
    {
        if ($this->cookie == null) {
            $this->cookie = new Cookie();
        }
        return $this->cookie;
    }

    public function getSessionPanier(): SessionPanier
    {
        $sessionPanier = null;

        if ($this->session->getItem('panier') === null) {
            $sessionPanier = new SessionPanier();
        } else {
            $sessionPanier = $this->session->getItem('panier');
        }
        return $sessionPanier;
    }

    private function getFilAriane(): FilAriane
    {
        $filAriane = null;

        if ($this->session->getItem('filAriane') == null) {
            $filAriane = new FilAriane();
        } else {
            $filAriane = $this->session->getItem('filAriane');
        }

        return $filAriane;
    }

    private function configurerEnvironnement(): void
    {
        if ($this->getServeur() === 'serveur-local') {
            error_reporting(E_ALL | E_STRICT);
        }
        date_default_timezone_set('America/Montreal');
    }

    public function getServeur(): string
    {
        // Vérifier la nature du serveur (local VS production)
        $env = 'null';
        //$_SERVER : quel serveur est en train de servir la page
        if ((substr($_SERVER['HTTP_HOST'], 0, 9) == 'localhost') ||
            (substr($_SERVER['HTTP_HOST'], 0, 7) == '192.168') ||
            (substr($_SERVER['SERVER_ADDR'], 0, 7) == '192.168')) {
            // sur mon poste local = on affiche les erreurs
            $env = 'serveur-local';
        } else {
            //timunix serveur
            $env = 'serveur-production';
        }
        return $env;
    }

    // Pour migrer griserie sur timunix, NE PAS UTILISER timunix2.cegep-ste-foy.qc.ca MAIS prendre localhost
    public function getPDO(): PDO
    {
        $environnement = $this->getServeur();
        $connexionBd = null;

        if ($this->pdo === null) {
            if ($environnement == "serveur-local") {
                $connexionBd = new ConnexionBD("localhost", "root", "root", "19_griserie");
                $this->pdo = $connexionBd->getNouvelleConnexionPDO();

            } else {
                $connexionBd = new ConnexionBD("localhost", "19_griserie", "sourisverte", "19_rpni3_griserie");
                $this->pdo = $connexionBd->getNouvelleConnexionPDO();
            }
        }
        //si pas nouvelle, elle retourne celle stockée en attribut en haut avec la première connexion du départ
        return $this->pdo;
    }

    public function getBlade(): BladeOne
    {
        if ($this->blade === null) {
            $cheminDossierVues = '../ressources/vues';
            $cheminDossierCache = '../ressources/cache';
            $this->blade = new BladeOne($cheminDossierVues, $cheminDossierCache, BladeOne::MODE_AUTO);
        }
        return $this->blade;
    }

    public function routerLaRequete(): void
    {
        $controleur = null;
        $action = null;

        // Déterminer le controleur responsable de traiter la requête
        if (isset($_GET['controleur'])) {
            $controleur = $_GET['controleur'];
        } else {
            $controleur = 'site';
        }

        // Déterminer l'action du controleur
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
        } else {
            $action = 'accueil';
        }

        // Instantier le bon controleur selon la page demandée
        if ($controleur === 'site') {
            $this->monControleur = new ControleurSite();
            switch ($action) {
                case 'accueil':
                    $this->monControleur->accueil();
                    break;
                case 'apropos':
                    $this->monControleur->apropos();
                    break;
                case 'afficherResultats':
                    $this->monControleur->afficherResultats();
                    break;
                default:
                    $this->monControleur->page404();
                    break;
            }
        } else if ($controleur === 'livre') {
            $this->monControleur = new ControleurLivre();
            switch ($action) {
                case 'index' :
                    $this->monControleur->index();
                    break;
                case 'fiche' :
                    $this->monControleur->fiche();
                    break;
                default :
                    header('Location: index.php?controleur=site&action=page404');
                    exit;
            }
        } else if ($controleur === 'panier') {
            $this->monControleur = new ControleurPanier();
            switch ($action) {
                case 'ajouterItem' :
                    $this->monControleur->ajouterItem();
                    break;
                case 'fiche' :
                    $this->monControleur->fiche();
                    break;
                case 'majQuantite' :
                    $this->monControleur->majQuantite();
                    break;
                case 'majLivraison' :
                    $this->monControleur->majLivraison();
                    break;
                case 'supprimerItem' :
                    $this->monControleur->supprimerItem();
                    break;
                default :
                    header('Location: index.php?controleur=site&action=page404');
                    exit;
            }

        } else if ($controleur === 'livraison') {
            $this->monControleur = new ControleurLivraison();

            if ($action == 'livraison') {
                $this->monControleur->livraison();
            }
            else if ($action == 'afficher') {
                    $this->monControleur->afficher();
            } else if ($action == 'valider') {
                $this->monControleur->valider();
            } else {
                header('Location: index.php?controleur=site&action=page404');
                exit;
            }

        } else if ($controleur === 'facturation') {
            $this->monControleur = new ControleurFacturation();

            if ($action == 'afficher') {
                $this->monControleur->afficher();
            } else if ($action == 'valider') {
                $this->monControleur->valider();
            } else {
                header('Location: index.php?controleur=site&action=page404');
                exit;
            }

        } else if ($controleur === 'validation') {
            $this->monControleur = new ControleurValidation();

            if ($action == 'afficher') {
                $this->monControleur->afficher();
            } else if ($action == 'supprimerItem') {
                $this->monControleur->supprimerItem();
            } else if ($action == 'inserer') {
                $this->monControleur->inserer();
            } else {
                header('Location: index.php?controleur=site&action=page404');
                exit;
            }
        }

        else if ($controleur === 'confirmation') {
            $this->monControleur = new ControleurConfirmation();

            if ($action == 'afficher') {
                $this->monControleur->afficher();
            }else {
                header('Location: index.php?controleur=site&action=page404');
                exit;
            }
        }

        else if ($controleur === 'client') {
            $this->monControleur = new ControleurClient();

            // Afficher l'écran de connexion
            if ($action == 'afficher') {
                $this->monControleur->afficher();
            } else if ($action == 'valider') {
                $this->monControleur->valider();
            }
            else if ($action == 'deconnecter') {
                $this->monControleur->deconnecter();
            }

            // Afficher l'écran de création d'un compte
            else if ($action == 'creer') {
                $this->monControleur->creer();
            }  else if ($action == 'inserer') {
                $this->monControleur->inserer();
            }  else if ($action == "courrielDisponibilite"){
                $this->monControleur->courrielDisponibilite();
            } else {
                header('Location: index.php?controleur=site&action=page404');
                exit;
            }
        } else {
            header('Location: index.php?controleur=site&action=page404');
            exit;
        }
    }
}