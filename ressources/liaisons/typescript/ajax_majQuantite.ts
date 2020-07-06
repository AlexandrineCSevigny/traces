/**
 * @Project Traces - majQuantite
 * @author Alexandrine C. Sevigny <asevigny@outlook.fr>
 * @date Septembre 2019 - Version 1.0.1
 */
export class ajax_majQuantite {

    private executerAjax_lier = null;

    public constructor() {
        this.executerAjax_lier = this.executerAjax.bind(this);
        this.initialiser();
    }

    private static retournerResultat(data, textStatus, jqXHR, id) {
        var idSelect = '#' + id;

        $(idSelect).closest('.panier__flex').find('.panier__itemPrixTexte').text(data['montantItem'] + '$');
        $('.panier__sousTotalPrix').text(data['sousTotal'] + '$');
        $('.panier__headerTotal .bold').text(data['sousTotal'] + '$');
        $('.panier__tpsPrix').text(data['tps'] + '$');
        $('.panier__LivraisonPrix').text(data['livraison'] + '$');
        $('.panier__prixPrix').text(data['total'] + '$');

        if(parseInt(data['nbItems'])>1) {
            $('.nbArticles').text(data['nbItems'] + ' articles');
            $('.panier__sousTotalNbArticle').text(data['nbItems'] + ' articles');
        } else {
            $('.nbArticles').text(data['nbItems'] + ' article');
            $('.panier__sousTotalNbArticle').text(data['nbItems'] + ' article');
        }

        if (parseInt(data['sousTotal']) >= 50) {
            $('#livraison').html('<option value="gratuit" selected="selected">Gratuit</option><option value = "standard" >Standard</option><option value = "prioritaire" >Prioritaire </option>');
        } else {
            $('#livraison').html('<option value = "standard" selected="selected">Standard</option><option value = "prioritaire" >Prioritaire </option>');
        }

        if (parseInt(data['sousTotal']) >= 50) {
            $('.panier__headerMessage').html(
                '<span class="panier__headerMessageTexte">Admissible à la livraison gratuite !</span>\n' +
                '                        <svg class="icone">\n' +
                '                            <use xlink:href="#icone_livraison_blanc"/>\n' +
                '                        </svg>'
            );
        } else {
            $('.panier__headerMessage').html('');
        }
    }

    private executerAjax(evenement) {

        var id = evenement.currentTarget;
        id = id.id;
        var value = evenement.currentTarget.value;

        $.ajax({
            url: 'index.php?controleur=panier&action=majQuantite&isbn=' + id,
            type: 'GET',
            data: 'quantite=' + value,
            dataType: 'json'
        }).done(function (data, textStatus, jqXHR) {
            ajax_majQuantite.retournerResultat(data, textStatus, jqXHR, id);
        });
    }

    private initialiser() {
        var arrInput = document.querySelectorAll('.quantiteSelect');
        for (var i = 0; i < arrInput.length; i++) {
            arrInput[i].addEventListener('change', this.executerAjax_lier);
        }
    }
}