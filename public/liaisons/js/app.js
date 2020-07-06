/**
 * @Project Traces - App
 * @author Camille Dion-Bolduc <camille.dion.bolduc@gmail.com>
 * @author Alexandrine C. Sevigny <asevigny@outlook.fr>
 * @author Emie Pelletier <emiepelletier@hotmail.com>
 * @date Septembre 2019 - Version 1.0.1
 */
define(["require", "exports", "./AffichageToggle", "./ajax_majQuantite", "./ajax_courriel", "./ajax_recherche", "./afficherMotDePasse", "./ajax_majLivraison", "./Validation", "./menuNav", "./BoutonRetour", "./AffichageInformationCarte"], function (require, exports, AffichageToggle_1, ajax_majQuantite_1, ajax_courriel_1, ajax_recherche_1, afficherMotDePasse_1, ajax_majLivraison_1, Validation_1, menuNav_1, BoutonRetour_1, AffichageInformationCarte_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    document.body.classList.add('js');
    var conteneurPage = document.querySelector('main div');
    var conteneurCatalogue = document.querySelector('.catalogue');
    var conteneurCreerCompte = document.querySelector('.creerClient');
    var conteneurConnexion = document.querySelector('.connexionClient');
    var conteneurPanier = document.querySelector('.panier');
    var conteneurTransaction = document.querySelector('.transaction');
    var conteneurValidation = document.querySelector('.validation');
    var conteneurFacturation = document.querySelector('.facturation');
    if (conteneurPage !== conteneurTransaction) {
        new menuNav_1.MenuNav();
        new ajax_recherche_1.Ajax_recherche();
    }
    if (conteneurPage == conteneurCatalogue) {
        new AffichageToggle_1.AffichageToggle();
    }
    if (conteneurPage == conteneurPanier || conteneurPage == conteneurValidation) {
        new ajax_majQuantite_1.ajax_majQuantite();
        if ($('#livraison').length) {
            new ajax_majLivraison_1.ajax_majLivraison();
        }
    }
    if (conteneurPage == conteneurFacturation) {
        new AffichageInformationCarte_1.AffichageInformationCarte();
    }
    // On regarde si on est dans la page de formulaire Créer un compte (Mandat A)
    if (conteneurPage == conteneurCreerCompte) {
        new ajax_courriel_1.ajax_courriel();
        new afficherMotDePasse_1.afficherMotDePasse();
    }
    if (conteneurPage == conteneurConnexion) {
        new afficherMotDePasse_1.afficherMotDePasse();
    }
    new Validation_1.Validation(messagesValidationClient);
    new BoutonRetour_1.BoutonRetour();
});
//# sourceMappingURL=app.js.map