// export class Ajax_tris {
//
//     private executerAjax_lier = null;
//
//     public constructor() {
//
//         this.executerAjax_lier = this.executerAjax.bind(this);
//         this.initialiser();
//     }
//
//     private initialiser() {
//         let listeTri = (<HTMLInputElement>document.querySelector('.tri'));
//         listeTri.addEventListener('change', this.executerAjax_lier);
//     }
//
//     private executerAjax(evenement) {
//         let valeurListe = (<HTMLSelectElement>document.querySelector('.tri')).value;
//
//         $.ajax({
//             url: 'index.php?controleur=livre&action=index',
//             type: 'get',
//             data: 'tri=' + valeurListe,
//             dataType: 'html'
//         })
//             .done(function (data, textStatus, jqXHR) {
//                 Ajax_tris.retournerResultat(data, textStatus, jqXHR);
//             })
//     }
//
//     private static retournerResultat(data, textStatus, jqXHR) {
//         let listeTri = (<HTMLInputElement>document.querySelector('.tri'));
//         let conteneurCartes = (<HTMLInputElement>document.querySelector('.cartes div'));
//
//         // conteneurCartes.innerHTML = data;
//         console.log(data);
//     }
// }
//# sourceMappingURL=Ajax_tris.js.map