/**
 * @Project Traces - App
 * @author Camille Dion-Bolduc <camille.dion.bolduc@gmail.com>
 * @author Alexandrine C. Sevigny <asevigny@outlook.fr>
 * @author Emie Pelletier <emiepelletier@hotmail.com>
 * @date Septembre 2019 - Version 1.0.1
 */

import {AffichageToggle} from "./AffichageToggle";
import {ajax_majQuantite} from "./ajax_majQuantite";
import {ajax_courriel} from "./ajax_courriel";
import {Ajax_recherche} from "./ajax_recherche";
import {afficherMotDePasse} from "./afficherMotDePasse";
import {ajax_majLivraison} from "./ajax_majLivraison";
import {Validation} from "./Validation";
import {MenuNav} from "./menuNav";
import {BoutonRetour} from "./BoutonRetour";
import {AffichageInformationCarte} from "./AffichageInformationCarte";

document.body.classList.add('js');

let conteneurPage = document.querySelector('main div');
let conteneurCatalogue = document.querySelector('.catalogue');
let conteneurCreerCompte = document.querySelector('.creerClient');
let conteneurConnexion = document.querySelector('.connexionClient');
let conteneurPanier = document.querySelector('.panier');
let conteneurTransaction = document.querySelector('.transaction');
let conteneurValidation = document.querySelector('.validation');
let conteneurFacturation = document.querySelector('.facturation');

if (conteneurPage !== conteneurTransaction) {
    new MenuNav();
    new Ajax_recherche();
}

if(conteneurPage == conteneurCatalogue){
    new AffichageToggle();
}

if(conteneurPage == conteneurPanier || conteneurPage == conteneurValidation) {
    new ajax_majQuantite();
    if ( $('#livraison').length ) {
        new ajax_majLivraison();
    }
}

if(conteneurPage == conteneurFacturation){
    new AffichageInformationCarte();
}

// On regarde si on est dans la page de formulaire Créer un compte (Mandat A)
if(conteneurPage == conteneurCreerCompte){
    new ajax_courriel();
    new afficherMotDePasse();
}
if(conteneurPage == conteneurConnexion){
    new afficherMotDePasse();
}

new Validation(messagesValidationClient);
new BoutonRetour();
