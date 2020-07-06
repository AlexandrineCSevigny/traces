/**
 * @file Affichage des informations à remplir pour insérer une carte de crédit
 * @author Emie Pelletier <1761129@etu.cegep-ste-foy.qc.ca>
 * @version 1
 */

export class AffichageInformationCarte {

    private boutonPaypal: HTMLElement = document.querySelector('[value=paypal]');
    private boutonCarteCredit: HTMLElement = document.querySelector('[value=carteCredit]');
    private conteneurInfoCarte : HTMLElement = document.querySelector('.facturation__informationsCredit');

    constructor() {
        this.boutonCarteCredit.addEventListener('click', this.afficherInfoCarte.bind(this));
        this.boutonPaypal.addEventListener('click', this.cacherInfoCarte.bind(this));
    }

    private afficherInfoCarte(event) {

        this.conteneurInfoCarte.classList.add('actif');
    }

    private cacherInfoCarte(event) {

        this.conteneurInfoCarte.classList.remove('actif');
    }
}
