/**
 * @file AffichageToggle
 * @author Emie Pelletier <1761129@etu.cegep-ste-foy.qc.ca>
 * @version 1
 */
define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var AffichageToggle = /** @class */ (function () {
        function AffichageToggle() {
            var bouton = document.querySelector('.catalogue__affichageBouton');
            var conteneurCarte = document.querySelector('.cartes div');
            //---- Vérifie s'il n'y a pas déjà un mode d'affichage choisie ----
            if (sessionStorage.getItem("modeAffichage")) {
                var modeAffichageEnCours = conteneurCarte.className;
                var modeAffichage = sessionStorage.getItem("modeAffichage");
                conteneurCarte.classList.toggle(modeAffichageEnCours);
                conteneurCarte.classList.toggle(modeAffichage);
                if (modeAffichage == conteneurCarte.className) {
                    bouton.classList.add(modeAffichage);
                }
            }
            else {
                //----  Par défaut, ajout le mode d'affichage grille  ----
                sessionStorage.setItem("modeAffichage", conteneurCarte.className);
                var modeAffichage = sessionStorage.getItem("modeAffichage");
                if (modeAffichage == conteneurCarte.className) {
                    bouton.classList.add(modeAffichage);
                }
            }
            bouton.addEventListener('click', this.changerAffichage.bind(this));
        }
        AffichageToggle.prototype.changerAffichage = function (event) {
            var conteneurCarte = document.querySelector('.cartes div');
            var modeAffichage = sessionStorage.getItem("modeAffichage");
            var bouton = document.querySelector('.catalogue__affichageBouton');
            conteneurCarte.classList.toggle('cartes--liste');
            conteneurCarte.classList.toggle('cartes--grille');
            bouton.classList.toggle('cartes--liste');
            bouton.classList.toggle('cartes--grille');
            //----  Maintient le mode d'affichage côté serveur  ----
            sessionStorage.setItem("modeAffichage", conteneurCarte.className);
        };
        return AffichageToggle;
    }());
    exports.AffichageToggle = AffichageToggle;
});
//# sourceMappingURL=AffichageToggle.js.map