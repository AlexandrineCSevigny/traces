<?php
/**
 * @Project Traces - ControleurValidation
 * @author Emie Pelletier <emiepelletier@hotmail.com>
 * @author Alexandrine C.Sévigny <asevigny@hotmail.fr>
 * @date Novembre 2019 - Version 1.0.1
 */
declare(strict_types=1);

namespace App;

use App\Modeles\Livre;
use App\Modeles\Categorie;

class FilAriane
{

    private $session = null;

    public function __construct()
    {
        $this->session = App::getInstance()->getSession();
    }

    public static function majFilArianne(): array
    {
        $fil = array();

        //Si le contrôleur est défini
        if (isset($_GET["controleur"])) {

            //Si le contrôleur n'est pas celui du site, nous sommes au deuxième niveau
            if ($_GET["controleur"] !== 'site') {
                switch (true) {
                    //Si l'action est d'afficher une liste de livres
                    case  $_GET["action"] === 'index' :

                        //Lien de retour vers l'accueil
                        $lien0 = array("titre" => "Accueil", "lien" => "index.php?controleur=site&action=accueil");

                        $fil[0] = $lien0;
                        //@todo adapter cet algo pour les catéries...

                        //Titre de la page
                        if (isset($_GET["nouveau"])) {
                            $fil[1] = array("titre" => "Nouveautés");
                        } else {
                            $fil[1] = array("titre" => "Catalogue");
                        }

                        if (isset($_GET["categorie"])) {

                            $categorieEnCours = Categorie::trouver($_GET["categorie"]);

                            if (isset($_GET["categorie"])) {
                                $fil[1] = array("titre" => "Catalogue", "lien" => "index.php?controleur=livre&action=index");
                                $fil[2] = array("titre" => $categorieEnCours->nom_fr);
                            }
                        }

                        break;

                    //Si l'action d'afficher une fiche de livre
                    case  $_GET["action"] === 'fiche' :

                        $session = App::getInstance()->getSession();

                        //Lien de retour vers l'accueil
                        $lien0 = array("titre" => "Accueil", "lien" => "index.php?controleur=site&action=accueil");

                        //Lien vers la liste des pages se qualifiant (catégorie, nouveauté...)

                        //@todo adapter cet algo pour les catéries...

                       if(isset($_SESSION['preferences']['categorie'])){
                           $categorieEnCours =  $_SESSION['preferences']['categorie'];
                        } else {
                           $categorieEnCours = $_GET['categorie'];
                        }

                        switch ($categorieEnCours) {
                            case 'nouveau':
                                $lien1 = array("titre" => "Nouveautés", "lien" => "index.php?controleur=livre&action=index&nouveau=" . $categorieEnCours);
                                break;

                            case 'catalogue':
                                $lien1 = array("titre" => "Catalogue", "lien" => "index.php?controleur=livre&action=index");
                                break;

                            default:
                                // Catégorie en cours
                                $idCategorie = $_SESSION['preferences']['categorie'];
                                $categorieEnCours = Categorie::trouver((string)$idCategorie);

                                // Page en cours
                                $numeroPage = $_SESSION['preferences']['numeroPage'];;

                                $lien1 = array("titre" => $categorieEnCours->nom_fr, "lien" => "index.php?controleur=livre&action=index&categorie=" . $idCategorie . "&page=" . $numeroPage);
                        }

                        $fil[0] = $lien0;
                        $fil[1] = $lien1;

                        if (isset($_GET["isbn"])) {
                            $livre = Livre::trouverParIsbn($_GET["isbn"]);
                            $fil[2] = array("titre" => $livre->formaterTitre($livre->__get("titre")));
                        }
                        break;
                }
            }
        }
        return $fil;
    }

    // Getter / Setter (magique)
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }
}