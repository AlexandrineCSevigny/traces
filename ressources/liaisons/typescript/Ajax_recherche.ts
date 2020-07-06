/**
 * @file Ajax_recherche
 * @author Emie Pelletier <1761129@etu.cegep-ste-foy.qc.ca>
 * @version 1
 */
export class Ajax_recherche {

    private executerAjax_lier = null;

    public constructor() {

        this.executerAjax_lier = this.executerAjax.bind(this);
        this.initialiser();
    }

    private initialiser() {
        let champRecherche = document.querySelector('.recherche__rechercherInput');
        champRecherche.addEventListener('keyup', this.executerAjax_lier);
    }

    private executerAjax(evenement) {
        let valeurChamp = (<HTMLInputElement>document.querySelector('.recherche__rechercherInput')).value;
        let valeurListe = (<HTMLSelectElement>document.querySelector('.recherche__sujetFiltre')).value;

        $.ajax({
            url: 'index.php?controleur=site&action=afficherResultats',
            type: 'get',
            data: 'valeurChamp=' + valeurChamp + '&valeurListe=' + valeurListe,
            dataType: 'html'
        })
            .done(function (data, textStatus, jqXHR) {
                Ajax_recherche.retournerResultat(data, textStatus, jqXHR);
            })
    }

    private static retournerResultat(data, textStatus, jqXHR) {
        let champRecherche = (<HTMLInputElement>document.querySelector('.recherche__rechercherInput'));
        let conteneurAideSaisie = (<HTMLInputElement>document.querySelector('.recherche__aideSaisie'));

        if (data !== '') {
            // -- Des résultats sont disponibles --
            conteneurAideSaisie.innerHTML= data;
            conteneurAideSaisie.style.display = 'block';
        } else {
            // -- Aucun résultat n'est disponible --
            conteneurAideSaisie.innerHTML= "";
            conteneurAideSaisie.style.display = 'none';

            // -- Aucun résultat n'est disponible mais le champ recherche est rempli --
            if(champRecherche.value !== ''){
                conteneurAideSaisie.style.display = 'block';
                conteneurAideSaisie.innerHTML = "<li class='recherche__aideSaisieLien'> Aucun résultat ne correspond à vos recherches.</li>";
            }
        }
    }
}