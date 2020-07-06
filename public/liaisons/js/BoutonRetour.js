/**
 * @file Bouton retour vers le haut de la page
 * @author Emie Pelletier <1761129@etu.cegep-ste-foy.qc.ca>
 * @version 1
 */
define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var BoutonRetour = /** @class */ (function () {
        function BoutonRetour() {
            this.bouton = document.querySelector('.js .boutonRetourHaut');
            this.bouton.addEventListener('click', this.remonterPage.bind(this));
            window.addEventListener('scroll', this.scroll.bind(this));
        }
        BoutonRetour.prototype.scroll = function (event) {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                this.bouton.style.animation = 'scale-in-center 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both';
            }
            else {
                this.bouton.style.animation = 'scale-out-center 0.3s cubic-bezier(0.550, 0.085, 0.680, 0.530) both';
            }
        };
        BoutonRetour.prototype.remonterPage = function (event) {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        };
        return BoutonRetour;
    }());
    exports.BoutonRetour = BoutonRetour;
});
//# sourceMappingURL=BoutonRetour.js.map