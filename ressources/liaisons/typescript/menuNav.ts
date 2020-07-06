/**
 * @file   Un menu mobile jQuery en amélioration progressive.
 * @author Ève Février <eve.fevrier@cegep-ste-foy.qc.ca>
 * @author Yves Hélie <yves.helie@cegep-ste-foy.qc.ca>
 * @author Alexandrine C. Sévigny <1136931@cegep-ste-foy.qc.ca>
 * @version 2.0
 */

export class MenuNav {

//*******************
// Déclaration d'objet(s)
//*******************
    private refMenu = $('.header .menu');
    private btnMenu = null;
    private lblOuvrir = 'Ouvrir';
    private lblFermer = 'Fermer';

    public constructor() {
        this.configurerNav();
    }

    private configurerNav(): void {
        // Création des boutons qui seront utilisés par le menu mobile
        this.btnMenu = $('<button>');
        this.btnMenu.addClass('menu__btnMenu');
        this.btnMenu.append($('<span>').addClass('menu__libelle screen-reader-only').html(this.lblOuvrir));

        // On ajoute le bouton pour le menu mobile
        $('.header .menu__logo').append(this.btnMenu);

        // On ajoute la classe --ferme au menu en général; par défaut il est caché avec JS
        this.refMenu.addClass('menu--ferme');

        // Création de l'écouteur d'événement pour le bouton du menu mobile
        this.refMenu.find('.menu__btnMenu').on('click', this.ouvrirFermerMenu.bind(this));
    }

    /**
     * Méthode pour basculer l'affichage du menu mobile en se basant sur la classe --ferme
     * @param evenement
     */
    private ouvrirFermerMenu(evenement): void {
        // Bascule de l'état du bouton
        this.refMenu.toggleClass('menu--ferme');

        // Bascule de l'état du menu
        $(evenement.currentTarget).next('.menu__liste').toggleClass('menu__liste--ferme');

        // Changement du libellé du bouton du menu mobile
        if (this.refMenu.hasClass('menu--ferme')) {
            $(evenement.currentTarget).find('.menu__libelle').html(this.lblOuvrir);
        } else {
            $(evenement.currentTarget).find('.menu__libelle').html(this.lblFermer);
        }
    }
}