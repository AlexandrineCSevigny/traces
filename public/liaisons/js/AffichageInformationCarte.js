/**
 * @file Affichage des informations à remplir pour insérer une carte de crédit
 * @author Emie Pelletier <1761129@etu.cegep-ste-foy.qc.ca>
 * @version 1
 */
define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var AffichageInformationCarte = /** @class */ (function () {
        function AffichageInformationCarte() {
            this.boutonPaypal = document.querySelector('[value=paypal]');
            this.boutonCarteCredit = document.querySelector('[value=carteCredit]');
            this.conteneurInfoCarte = document.querySelector('.facturation__informationsCredit');
            this.boutonCarteCredit.addEventListener('click', this.afficherInfoCarte.bind(this));
            this.boutonPaypal.addEventListener('click', this.cacherInfoCarte.bind(this));
        }
        AffichageInformationCarte.prototype.afficherInfoCarte = function (event) {
            this.conteneurInfoCarte.classList.add('actif');
        };
        AffichageInformationCarte.prototype.cacherInfoCarte = function (event) {
            this.conteneurInfoCarte.classList.remove('actif');
        };
        return AffichageInformationCarte;
    }());
    exports.AffichageInformationCarte = AffichageInformationCarte;
});
//# sourceMappingURL=AffichageInformationCarte.js.map