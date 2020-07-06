/**
 * @file AffichageToggle
 * @author Emie Pelletier <1761129@etu.cegep-ste-foy.qc.ca>
 * @version 1
 */

export class AffichageToggle {
    constructor() {
        let bouton = document.querySelector('.catalogue__affichageBouton');
        let conteneurCarte = document.querySelector('.cartes div');

        //---- Vérifie s'il n'y a pas déjà un mode d'affichage choisie ----
        if (sessionStorage.getItem("modeAffichage")) {

            let modeAffichageEnCours = conteneurCarte.className;
            let modeAffichage = sessionStorage.getItem("modeAffichage");

            conteneurCarte.classList.toggle(modeAffichageEnCours);
            conteneurCarte.classList.toggle(modeAffichage);

            if(modeAffichage == conteneurCarte.className){
                bouton.classList.add(modeAffichage)
            }
        }
        else {
            //----  Par défaut, ajout le mode d'affichage grille  ----
            sessionStorage.setItem("modeAffichage", conteneurCarte.className);
            let modeAffichage = sessionStorage.getItem("modeAffichage");

            if(modeAffichage == conteneurCarte.className){
                bouton.classList.add(modeAffichage)
            }
        }

        bouton.addEventListener('click', this.changerAffichage.bind(this));
    }

    private changerAffichage(event) {
        const conteneurCarte = document.querySelector('.cartes div');
        const modeAffichage = sessionStorage.getItem("modeAffichage");
        const bouton = document.querySelector('.catalogue__affichageBouton');

        conteneurCarte.classList.toggle('cartes--liste');
        conteneurCarte.classList.toggle('cartes--grille');

        bouton.classList.toggle('cartes--liste');
        bouton.classList.toggle('cartes--grille');

        //----  Maintient le mode d'affichage côté serveur  ----
        sessionStorage.setItem("modeAffichage", conteneurCarte.className);
    }
}

