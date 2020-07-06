/**
 * @Project Traces - majQuantite
 * @author Alexandrine C. Sevigny <asevigny@outlook.fr>
 * @date Septembre 2019 - Version 1.0.1
 */
define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var ajax_majLivraison = /** @class */ (function () {
        function ajax_majLivraison() {
            this.executerAjax_lier = null;
            this.executerAjax_lier = this.executerAjax.bind(this);
            this.initialiser();
        }
        ajax_majLivraison.retournerResultat = function (data, textStatus, jqXHR) {
            $('.panier__LivraisonPrix').text(data['livraison'] + '$');
            $('.panier__prixPrix').text(data['total'] + '$');
            $('.panier__LivraisonDate .date').text(data['delai']);
        };
        ajax_majLivraison.prototype.executerAjax = function (evenement) {
            var value = evenement.currentTarget.value;
            $.ajax({
                url: 'index.php?controleur=panier&action=majLivraison',
                type: 'GET',
                data: 'livraison=' + value,
                dataType: 'json'
            }).done(function (data, textStatus, jqXHR) {
                ajax_majLivraison.retournerResultat(data, textStatus, jqXHR);
            });
        };
        ajax_majLivraison.prototype.initialiser = function () {
            var select = document.getElementById('livraison');
            select.addEventListener('change', this.executerAjax_lier);
        };
        return ajax_majLivraison;
    }());
    exports.ajax_majLivraison = ajax_majLivraison;
});
//# sourceMappingURL=ajax_majLivraison.js.map