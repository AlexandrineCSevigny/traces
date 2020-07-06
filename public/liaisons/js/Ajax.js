// export class Ajax {
//
//     constructor() {
//
//         this.initialiser();
//     }
//
//     private initialiser(): void {
//         let champRecherche = document.querySelector('.recherche__rechercherInput');
//
//         champRecherche.addEventListener('keyup', this.executerAjaxRecherche.bind(this));
//     }
//
//
//     public retournerResultat(data, textStatus, jqXHR)
//     {
//
//         // $('.recherche__rechercherInput').text(data);
//         console.log(data);
//     }
//
//     public executerAjaxRecherche(evenement)
//     {
//         $.ajax({
//             url: 'index.php?controleur=site&action=afficherResultats',
//             type: 'get',
//             dataType: 'html'
//         })
//             .done(function (data, textStatus, jqXHR) {
//                 retournerResultat(data, textStatus, jqXHR);
//             })
//     }
// }
//
//# sourceMappingURL=Ajax.js.map