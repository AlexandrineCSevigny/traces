/**
 * @title Basculer affichage du mot de passe (Connexion et créer un compte)
 * @author Camille Dion-Bolduc
 * @project Traces
 * @version 1.0.1
 */

export class afficherMotDePasse {

    private refMotDePasse: HTMLElement = null;
    private refAfficherMotDePasse: HTMLInputElement = null;
    private AfficherMotDePasse_lier = null;

    public constructor() {
        // Lier ma function pour mon écouteur d'évènement
        this.AfficherMotDePasse_lier = this.AfficherMotDePasse.bind(this);
        this.initialiser();
    }

   public static basculerTypeMdp(){
        // Je m'en vais chercher ma référence à mon champ de mot de passe
        let cible = <HTMLInputElement>document.getElementById('mot_de_passe');

        // Si le type est en password, on les enlève pour que ça soit en caractères alpha-numériques
        if (cible.type == "password"){
            cible.type = "text";

        // Sinon, on le recache avec des étoiles (donc, type password)
        } else if(cible.type == "text"){
            cible.type = "password";
        }
    }

    private AfficherMotDePasse():void {
        // On prend nos références d'éléments, le bouton à cocher pour changer l'affichage
        // Et la référence de l'input de mot de passe
        this.refAfficherMotDePasse = document.querySelector('.afficherMdp');
        this.refMotDePasse = document.getElementById('mot_de_passe');

        // Si c'est coché ET décoché on appelle la fonction pour basculer l'affichage
        if (this.refAfficherMotDePasse.checked === true){
            afficherMotDePasse.basculerTypeMdp();
        }
        else if (this.refAfficherMotDePasse.checked === false) {
            afficherMotDePasse.basculerTypeMdp(); }
    }

    private initialiser() {
        // Au changement de mon bouton radio, j'exécute afficherMotDePasse
        var boutonAfficherMdp = document.querySelector('.afficherMdp');
        boutonAfficherMdp.addEventListener('change', this.AfficherMotDePasse);
    }
}