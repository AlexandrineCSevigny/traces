define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    /**
     * @file Ajax_recherche
     * @author Emie Pelletier <1761129@etu.cegep-ste-foy.qc.ca>
     * @version 1
     */
    var Ajax_recherche = /** @class */ (function () {
        function Ajax_recherche() {
            this.executerAjax_lier = null;
            this.executerAjax_lier = this.executerAjax.bind(this);
            this.initialiser();
        }
        Ajax_recherche.prototype.initialiser = function () {
            var champRecherche = document.querySelector('.recherche__rechercherInput');
            champRecherche.addEventListener('keyup', this.executerAjax_lier);
        };
        Ajax_recherche.prototype.executerAjax = function (evenement) {
            var valeurChamp = document.querySelector('.recherche__rechercherInput').value;
            var valeurListe = document.querySelector('.recherche__sujetFiltre').value;
            $.ajax({
                url: 'index.php?controleur=site&action=afficherResultats',
                type: 'get',
                data: 'valeurChamp=' + valeurChamp + '&valeurListe=' + valeurListe,
                dataType: 'html'
            })
                .done(function (data, textStatus, jqXHR) {
                Ajax_recherche.retournerResultat(data, textStatus, jqXHR);
            });
        };
        Ajax_recherche.retournerResultat = function (data, textStatus, jqXHR) {
            var champRecherche = document.querySelector('.recherche__rechercherInput');
            var conteneurAideSaisie = document.querySelector('.recherche__aideSaisie');
            if (data !== '') {
                // -- Des résultats sont disponibles --
                conteneurAideSaisie.innerHTML = data;
                conteneurAideSaisie.style.display = 'block';
            }
            else {
                // -- Aucun résultat n'est disponible --
                conteneurAideSaisie.innerHTML = "";
                conteneurAideSaisie.style.display = 'none';
                // -- Aucun résultat n'est disponible mais le champ recherche est rempli --
                if (champRecherche.value !== '') {
                    conteneurAideSaisie.style.display = 'block';
                    conteneurAideSaisie.innerHTML = "<li class='recherche__aideSaisieLien'> Aucun résultat ne correspond à vos recherches.</li>";
                }
            }
        };
        return Ajax_recherche;
    }());
    exports.Ajax_recherche = Ajax_recherche;
});
//# sourceMappingURL=Ajax_recherche.js.map