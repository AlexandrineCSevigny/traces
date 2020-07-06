/**
 * @Project Traces - majQuantite
 * @author Alexandrine C. Sevigny <asevigny@outlook.fr>
 * @date Septembre 2019 - Version 1.0.1
 */

export class ajax_majLivraison {

    private executerAjax_lier = null;

    public constructor() {
        this.executerAjax_lier = this.executerAjax.bind(this);
        this.initialiser();
    }

    private static retournerResultat(data, textStatus, jqXHR) {

        $('.panier__LivraisonPrix').text(data['livraison'] + '$');
        $('.panier__prixPrix').text(data['total'] + '$');
        $('.panier__LivraisonDate .date').text(data['delai']);

    }

    private executerAjax(evenement) {
        var value = evenement.currentTarget.value;

        $.ajax({
            url: 'index.php?controleur=panier&action=majLivraison',
            type: 'GET',
            data: 'livraison=' + value,
            dataType: 'json'
        }).done(function (data, textStatus, jqXHR) {
            ajax_majLivraison.retournerResultat(data, textStatus, jqXHR);
        });
    }

    private initialiser() {
        var select = document.getElementById('livraison');
        select.addEventListener('change', this.executerAjax_lier);

    }
}