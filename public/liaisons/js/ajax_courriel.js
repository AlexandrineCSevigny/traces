/**
 * @author Camille Dion-Bolduc
 * @project Traces
 * @version 1.0.2
 * @date Samedi 9 novembre 2019 (Version finale)
 */
define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var ajax_courriel = /** @class */ (function () {
        function ajax_courriel() {
            this.executerAjax_lier = null;
            // Lier ma function pour mon écouteur d'évènement
            this.executerAjax_lier = this.executerAjaxCourriel.bind(this);
            this.initialiser();
        }
        ajax_courriel.retournerResultatCourriel = function (data, textStatus, jqXHR) {
            // Si je reçoit 1, c'est que le courriel est déjà utilisé
            if (data == 1) {
                document.querySelector('.formulaire__courrielDisponible').innerHTML = "<svg class=\"formulaire__validation\"><use xlink:href=\"#icone_form_erreur\"/></svg>" + "Ce courriel est déjà utilisé par un utilisateur.";
            }
            // Si je reçoit 0, c'est qu'il s'agit d'un courriel disponible
            // Je vide aussi mon champ d'erreur si mon input est vide
            if (data == "" || data == 0) {
                document.querySelector('.formulaire__courrielDisponible').innerHTML = "";
            }
        };
        ajax_courriel.prototype.executerAjaxCourriel = function (evenement) {
            // Ici je dois envoyer mon courriel (string) rentré dans mon input dans ma querystring
            // À mon modèle Client qui a une méthode courrielDisponibilite pour appeller à son tour une méthode dans Client
            // Pour rechercher un client dans la DB selon le courriel validé reçu
            var valueCourriel = evenement.currentTarget.value;
            $.ajax({
                url: 'index.php?controleur=client&action=courrielDisponibilite&courriel=' + valueCourriel,
                type: 'GET',
                data: 'courriel=' + valueCourriel,
                dataType: 'html'
            })
                .done(function (data, textStatus, jqXHR) {
                ajax_courriel.retournerResultatCourriel(data, textStatus, jqXHR);
            });
        };
        ajax_courriel.prototype.initialiser = function () {
            // Au blur de mon champ spécifié par mon querySelector (input de courriel dans Créer un compte), j'ajoute ma méthode Ajax
            var champCourriel = document.querySelector('.courrielAjax');
            champCourriel.addEventListener('blur', this.executerAjax_lier);
        };
        return ajax_courriel;
    }());
    exports.ajax_courriel = ajax_courriel;
});
//# sourceMappingURL=ajax_courriel.js.map