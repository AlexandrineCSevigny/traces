<?php
/**
 * @Project Traces - Utilitaires
 * @author Camille Dion-Bolduc <camille.dion.bolduc@gmail.com>
 * @author Alexandrine C. Sevigny <asevigny@outlook.fr>
 * @author Emie Pelletier <emiepelletier@hotmail.com>
 * @date Septembre 2019 - Version 1.0.1
 */

namespace App;

use \datetime;
use App\App;

class Utilitaires
{

    public function __construct()
    {
    }

    /*
    * @method formaterDate()
    * @desc Prend $date de la BD et la met sous forme de mot selon notre timezone locale
    * @param string - $date prise de la BD
    * @return string - Retourne la $dateFormatee qui est la même transformée verbalement
    */
    public static function formaterDate(string $date): string
    {
        setlocale(LC_TIME, "fr_CA");
        $date = new DateTime($date);

        //if (this.cookie === "fr_CA")... (Français)
        $dateFormatee = strftime("%A %d %B %Y", $date->getTimestamp());

        //if (this.cookie === "en_CA")... (Anglais)
        // On met le mois devant le jour du mois
        //$dateFormatee = strftime("%A %B %d %Y", $date->getTimestamp());
        return $dateFormatee;
    }

    /*
    * @method ISBNToEAN
    * @desc Convertit un ISBN en format EAN
    * @param string - ISBN à convertir
    * @return string - ISBN converti en EAN, ou FALSE si erreur dans le format ou la conversion
    */
    public static function ISBNToEAN(string $strISBN): string
    {
        $myFirstPart = $mySecondPart = $myEan = $myTotal = "";
        if ($strISBN == "")
            return false;
        $strISBN = str_replace("-", "", $strISBN);
        // ISBN-10
        if (strlen($strISBN) == 10) {
            $myEan = "978" . substr($strISBN, 0, 9);
            $myFirstPart = intval(substr($myEan, 1, 1)) + intval(substr($myEan, 3, 1)) + intval(substr($myEan, 5, 1)) + intval(substr($myEan, 7, 1)) + intval(substr($myEan, 9, 1)) + intval(substr($myEan, 11, 1));
            $mySecondPart = intval(substr($myEan, 0, 1)) + intval(substr($myEan, 2, 1)) + intval(substr($myEan, 4, 1)) + intval(substr($myEan, 6, 1)) + intval(substr($myEan, 8, 1)) + intval(substr($myEan, 10, 1));
            $tmp = intval(substr((string)(3 * $myFirstPart + $mySecondPart), -1));
            $myControl = ($tmp == 0) ? 0 : 10 - $tmp;

            return $myEan . $myControl;
        } // ISBN-13
        else if (strlen($strISBN) == 13) return $strISBN;
        // Autre
        else return false;
    }

    /*
    * @method formaterPrix()
    * @desc Ajoute/contraint deux chiffres après une virgule (Ex: 15$ -> 15$ et 15,811133322 -> 15,81)
    * @param string - $prix (devise) à convertir en notation française
    * @return string - $prix avec une virgule et deux chiffres ajoutés
    */
    public static function formaterPrix(string $prix): string
    {
        $prix = number_format((float)$prix, 2, ',', '');
        return $prix;
    }

    /*
    * @method formaterTitre()
    * @desc Prend l'article (déterminant mis entre parenthèse à la fin d'un titre), enlève les parenthèse et le met au début du titre
    * @param string - $titre du livre
    * @return string - $chaine retourne le $titre et le $déterminant concaténé
    */
    public static function formaterTitre(string $titre): string
    {
        // Vérifie s'il y a un article (ex : L') dans le titre
        if (strpos($titre, "(") !== -1) {
            // On récupère l'article
            $determinant = substr($titre, strpos($titre, "("));

            // On retire l'article du titre, met le titre en minuscule et concatène l'article et le titre
            $titre = str_replace($determinant, "", $titre);
            $titre = strtolower($titre);
            $chaine = $determinant . $titre;

            // On ajoute un espace ou non selon si l'article contient un apostrophe
            $chaine = str_replace("(", "", $chaine);
            if (strpos($determinant, "'") !== false) {
                $chaine = str_replace(")", "", $chaine);
            } else {
                $chaine = str_replace(")", " ", $chaine);
            }
        }
        return $chaine;
    }

    /*
    * @method raccourcirTexte()
    * @desc On va le transformer en tableau avec la fonction explode()
    * @desc On va couper le tableau selon la longeur en argument et on reforme le tableau avec implode()
    * @param string - Prend $texte - une actualité ou une catégorie
    * @param int - Prend $longeur - une longeur de mots envoyés en arguments
    * @return string - Retourne le texte de l'actualité coupée, $actualiteCoupee
    */
    public static function raccourcirTexte(string $texte, int $longeur): string
    {
        // Raccourcir le texte envoyé en argument (Actualités, catégories, etc...)
        // On l'explose la string en tableau, espace délimite les mots
        $arrTexte = explode(" ", $texte);
        // On coupe le tableau selon la longeur mise en argument
        $texteCoupe = array_splice($arrTexte, 0, $longeur);
        // On le remet en string et on ajoute notre fin d'actualités
        $texteCoupe = implode(" ", $texteCoupe) . " [ ... ]";

        return $texteCoupe;
    }

    public static function validerChamp(string $nomChamp, $motif, $messagesJson, array $tValidation, bool $champRequis): array
    {
        $valeurChamp = '';
        $champValide = false;
        $message = '';

        if (isset($_POST[$nomChamp])) {

            $valeurChamp = trim($_POST[$nomChamp]);

            if ($valeurChamp == '' && $champRequis == true) {
                $message = $messagesJson[$nomChamp]['vide'];
            } else {
                $resultatValidation = preg_match($motif, $_POST[$nomChamp]);

                if ($resultatValidation == false) {
                    $message = $messagesJson[$nomChamp]['motif'];
                } else {
                    $champValide = true;
                }
            }
        } else {
            if ($champRequis == false) {
                $champValide = true;
            } else {
                $message = $messagesJson[$nomChamp]['vide'];
            }
        }

        $tValidation[$nomChamp] = ['valeur' => $valeurChamp, "champValide" => $champValide, "message" => $message];
        return $tValidation;
    }

    public static function validerDateExpiration(string $moisCarte, string $anneeCarte, $tMessagesJson, array $tValidation): array
    {
        $moisCarte = $_POST[$moisCarte];
        $anneeCarte = $_POST[$anneeCarte];

        $champValide = false;
        $message = '';

        if (checkdate(intVal($moisCarte), 01, intval($anneeCarte))) {
            $dateButoir = new DateTime($anneeCarte . '-' . $moisCarte . '-' . '01');
            $dateAujourdhui = new DateTime();

            if ($dateAujourdhui < $dateButoir) {
                $champValide = true;
            } else {
                $message = $tMessagesJson['expirationCarte']['expire'];
            }
        }

        $tValidation['expirationCarte'] = ['valeurMois' => $moisCarte, 'valeurAnnee' => $anneeCarte, "champValide" => $champValide, "message" => $message];
        return $tValidation;
    }

    public static function verifierConnexionClient(): bool
    {
        $session = App::getInstance()->getSession();
        $idClient = $session->getItem('client');

        if ($idClient !== null) {
            return true;
        } else {
            return false;
        }
    }
}