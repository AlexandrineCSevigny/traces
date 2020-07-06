/**
 * @file Validation
 * @author Emie Pelletier <1761129@etu.cegep-ste-foy.qc.ca>
 * @author Alexandrine C. Sévigny <asevigny@hotmail.fr>
 * @author Camille Dion-Bolduc
 * @version 1
 */

export class Validation {

    private messagesValidation: JSON;

    private refLivraisonNom: HTMLInputElement = null;
    private refLivraisonPrenom: HTMLInputElement = null;
    private refLivraisonAdresse: HTMLInputElement = null;
    private refLivraisonVille: HTMLInputElement = null;
    private refLivraisonProvince: HTMLSelectElement = null;
    private refLivraisonCodePostal: HTMLInputElement = null;

    private refFacturationModePaiement: Array<HTMLElement> = null;
    private refFacturationCarteCredit: Array<HTMLElement> = null;
    private refFacturationNomCarte: HTMLInputElement = null;
    private refFacturationNumeroCarte: HTMLInputElement = null;
    private refFacturationCode: HTMLInputElement = null;
    private refFacturationMoisCarte: HTMLSelectElement = null;
    private refFacturationAnneeCarte: HTMLSelectElement = null;
    private refFacturationNom: HTMLInputElement = null;
    private refFacturationPrenom: HTMLInputElement = null;
    private refFacturationAdresse: HTMLInputElement = null;
    private refFacturationVille: HTMLInputElement = null;
    private refFacturationProvince: HTMLSelectElement = null;
    private refFacturationCodePostal: HTMLInputElement = null;

    private refMode: boolean = null;


    //---- Références des inputs (Connexion) -----
    private refConnexionCourriel: HTMLInputElement = null;
    private refConnexionMdp: HTMLInputElement = null;

    //---- Références des inputs (Créer un compte) -----
    private refCreerPrenom: HTMLInputElement = null;
    private refCreerNom: HTMLInputElement = null;
    private refCreerTelephone: HTMLInputElement = null;
    private refCreerCourriel: HTMLInputElement = null;
    private refCreerMdp: HTMLInputElement = null;

    //---- Attributs écouteurs d'événements -----
    private validerChamp_lier: any = null;
    private validerBoutonsRadios_lier: any = null;
    private validerListe_lier: any = null;
    private verifierMode_lier: any = null;


    public constructor(messagesValidation) {
        this.messagesValidation = messagesValidation;
        this.initialiser();
    }

    // Initialisation des evenements et de la valdiation
    private initialiser(): void {
        let conteneurPage = document.querySelector('main div');
        let conteneurLivraison = document.querySelector('.livraison');
        let conteneurFacturation = document.querySelector('.facturation');
        let conteneurFacturationAdresse = document.querySelector('.facturation__adresse--nouvelle');
        let conteneurConnexion = document.querySelector('.connexionClient');
        let conteneurCreer = document.querySelector('.creerClient');


        //---- Initialisation des écouteurs d'événements ----
        this.validerChamp_lier = this.validerChamp.bind(this);
        this.validerBoutonsRadios_lier = this.validerBoutonsRadios.bind(this);
        this.validerListe_lier = this.validerListe.bind(this);
        this.verifierMode_lier = this.validerMode.bind(this);

        // Page de livraison
        if (conteneurPage == conteneurLivraison) {

            //---- Initialisation des attributs ----
            this.refLivraisonNom = document.querySelector('.livraison__nom');
            this.refLivraisonPrenom = document.querySelector('.livraison__prenom');
            this.refLivraisonAdresse = document.querySelector('.livraison__adresse');
            this.refLivraisonVille = document.querySelector('.livraison__ville');
            this.refLivraisonProvince = document.querySelector('.livraison__province');
            this.refLivraisonCodePostal = document.querySelector('.livraison__codePostal');

            //---- Écouteurs d'événements ----
            this.refLivraisonNom.addEventListener("blur", this.validerChamp_lier);
            this.refLivraisonPrenom.addEventListener("blur", this.validerChamp_lier);
            this.refLivraisonAdresse.addEventListener("blur", this.validerChamp_lier);
            this.refLivraisonVille.addEventListener("blur", this.validerChamp_lier);
            this.refLivraisonProvince.addEventListener("blur", this.validerListe_lier);
            this.refLivraisonCodePostal.addEventListener("blur", this.validerChamp_lier);
        }

        // Page de facturaton
        if (conteneurPage == conteneurFacturation) {

            //---- Initialisation des attributs ----
            this.refFacturationModePaiement = Array.apply(null, document.querySelectorAll('[name=modePaiement]'));
            this.refFacturationCarteCredit = Array.apply(null, document.querySelectorAll('[name=cartesCredit]'));


            if (conteneurFacturationAdresse) {
                this.refFacturationNom = document.querySelector('.facturation__nom');
                this.refFacturationPrenom = document.querySelector('.facturation__prenom');
                this.refFacturationAdresse = document.querySelector('.facturation__adresse');
                this.refFacturationVille = document.querySelector('.facturation__ville');
                this.refFacturationProvince = document.querySelector('.facturation__province');
                this.refFacturationCodePostal = document.querySelector('.facturation__codePostal');
            }

            //---- Écouteurs d'événements ----
            for (let intCpt = 0; intCpt < this.refFacturationModePaiement.length; intCpt++) {
                this.refFacturationModePaiement[intCpt].addEventListener("blur", this.validerBoutonsRadios_lier);
                this.refFacturationModePaiement[intCpt].addEventListener("blur", this.verifierMode_lier);
            }

            for (let intCpt = 0; intCpt < this.refFacturationCarteCredit.length; intCpt++) {
                this.refFacturationCarteCredit[intCpt].addEventListener("blur", this.validerBoutonsRadios_lier);
            }

            if (conteneurFacturationAdresse) {
                this.refFacturationNom.addEventListener("blur", this.validerChamp_lier);
                this.refFacturationPrenom.addEventListener("blur", this.validerChamp_lier);
                this.refFacturationAdresse.addEventListener("blur", this.validerChamp_lier);
                this.refFacturationVille.addEventListener("blur", this.validerChamp_lier);
                this.refFacturationProvince.addEventListener("blur", this.validerListe_lier);
                this.refFacturationCodePostal.addEventListener("blur", this.validerChamp_lier);
            }
        }

        // ---- Bind les input selon le conteneur - Connexion -----
        if (conteneurPage == conteneurConnexion) {
            this.refConnexionCourriel = document.querySelector('.connexion__courriel');
            this.refConnexionMdp = document.querySelector('.connexion__mdp');

            this.refConnexionCourriel.addEventListener("blur", this.validerChamp_lier);
            this.refConnexionMdp.addEventListener("blur", this.validerChamp_lier);
        }

        // ---- Bind les input selon le conteneur - Créer un compte -----
        if (conteneurPage == conteneurCreer) {
            this.refCreerPrenom = document.querySelector('.creer__prenom');
            this.refCreerNom = document.querySelector('.creer__nom');
            this.refCreerTelephone = document.querySelector('.creer__telephone');
            this.refCreerCourriel = document.querySelector('.creer__courriel');
            this.refCreerMdp = document.querySelector('.creer__mdp');

            this.refCreerPrenom.addEventListener("blur", this.validerChamp_lier);
            this.refCreerNom.addEventListener("blur", this.validerChamp_lier);
            this.refCreerTelephone.addEventListener("blur", this.validerChamp_lier);
            this.refCreerCourriel.addEventListener("blur", this.validerChamp_lier);
            this.refCreerMdp.addEventListener("blur", this.validerChamp_lier);
        }
    }

    // Validation des input de type texte
    private validerChamp(evenement): void {
        const element = evenement.currentTarget;
        const motif = element.pattern;
        const valeurChamp = element.value;

        if (valeurChamp === '') {
            this.afficherErreur(element, 'vide');
        } else {
            let resultatMotif = this.verifierMotif(valeurChamp, motif);

            if (resultatMotif === true) {
                this.afficherSucces(element);
            } else {
                this.afficherErreur(element, 'motif');
            }
        }
    }

    // Validation des input de type slect
    private validerListe(evenement): void {
        const element = evenement.currentTarget;
        const valeurChamp = element.value;
        let conteneurFacturationCredit = document.querySelector('.facturation__informationsCredit .actif');

        if (valeurChamp == '') {
            this.afficherErreur(element, 'vide');
        } else {
            this.afficherSucces(element);
        }

        //Validation de la date d'expiration
        if (this.refMode == true) {
            if (this.refFacturationMoisCarte.value !== '' && this.refFacturationAnneeCarte.value !== '') {
                if (this.validerDateExpiration() == true) {
                    this.afficherSucces(element);
                } else {
                    this.afficherErreur(element, 'expire');
                }
            }
        }
    }

    // Validation de la date de carte de crédit pour vérifier l'expiration
    private validerDateExpiration(): boolean {
        let dateSaisie = new Date(this.refFacturationAnneeCarte.value + '-' + this.refFacturationMoisCarte.value + '-01');
        let today = new Date();

        return dateSaisie.getTime() >= today.getTime();
    }

    // Validation des boutons radios
    private validerBoutonsRadios(evenement): void {
        const element = evenement.currentTarget;
        const conteneurFormulaire = element.closest('.ctnForm');
        const champValidation = conteneurFormulaire.querySelector('.validation__message');

        if (element.checked) {
            champValidation.innerHTML = '';
        } else {
            champValidation.innerHTML = "<svg class='formulaire__validation'>\n" + "<use xlink:href='#icone_form_erreur'>&nbsp;</use>\n" + "</svg>" + this.messagesValidation[element.name]['vide'];
        }
    }

    // Validation des patterns
    private verifierMotif(valeurChamp, motif): boolean {
        const regExp = new RegExp(motif);
        return regExp.test(valeurChamp);
    }

    // Méthode qui gère le succès d'un champs
    private afficherSucces(element): void {
        const conteneurFormulaire = element.closest('.ctnForm');
        const champValidation = conteneurFormulaire.querySelector('.validation__message');

        // Retire l'icone d'erreur s'il est présent
        champValidation.innerHTML = '';

        // Ajout de l'icone de succès
        champValidation.innerHTML = '<svg class="formulaire__validation formulaire__validation--succes">\n' + '<use xlink:href="#icone_form_valide"></use>\n' + '</svg>'
        champValidation.classList.add('validation__message--succes');
    }

    // Affichage de l'erreur et retrait de l'icone de succès
    private afficherErreur(element, typeMessage): void {
        const conteneurFormulaire = element.closest('.ctnForm');
        const champValidation = conteneurFormulaire.querySelector('.validation__message');

        // Ajout de l'icone d'erreur
        champValidation.classList.remove('validation__message--succes');
        champValidation.innerHTML = "<svg class='formulaire__validation'>\n" + "<use xlink:href='#icone_form_erreur'>&nbsp;</use>\n" + "</svg>" + this.messagesValidation[element.name][typeMessage];
    }

    // Valide le mode de paiement pour limiter la validation selon le choix
    private validerMode(evenement): void {
        const element = evenement.currentTarget;

        if (element.value == 'carteCredit') {
            this.refMode = true;
            this.initialiserCarte();
        }
        else {
            this.refMode = false;
        }
    }

    // Méthode d'initialisation des éléments pour les cartes de crédit
    private initialiserCarte(): void {
        this.refFacturationNomCarte = document.querySelector('.facturation__nomCarte');
        this.refFacturationNumeroCarte = document.querySelector('.facturation__numeroCarte');
        this.refFacturationCode = document.querySelector('.facturation__code');
        this.refFacturationMoisCarte = document.querySelector('.facturation__moisCarte');
        this.refFacturationAnneeCarte = document.querySelector('.facturation__anneeCarte');

        this.refFacturationNomCarte.addEventListener("blur", this.validerChamp_lier);
        this.refFacturationNumeroCarte.addEventListener("blur", this.validerChamp_lier);
        this.refFacturationCode.addEventListener("blur", this.validerChamp_lier);
        this.refFacturationMoisCarte.addEventListener("blur", this.validerListe_lier);
        this.refFacturationAnneeCarte.addEventListener("blur", this.validerListe_lier);
    }
}