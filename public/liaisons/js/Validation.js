/**
 * @file Validation
 * @author Emie Pelletier <1761129@etu.cegep-ste-foy.qc.ca>
 * @author Alexandrine C. Sévigny <asevigny@hotmail.fr>
 * @author Camille Dion-Bolduc
 * @version 1
 */
define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var Validation = /** @class */ (function () {
        function Validation(messagesValidation) {
            this.refLivraisonNom = null;
            this.refLivraisonPrenom = null;
            this.refLivraisonAdresse = null;
            this.refLivraisonVille = null;
            this.refLivraisonProvince = null;
            this.refLivraisonCodePostal = null;
            this.refFacturationModePaiement = null;
            this.refFacturationCarteCredit = null;
            this.refFacturationNomCarte = null;
            this.refFacturationNumeroCarte = null;
            this.refFacturationCode = null;
            this.refFacturationMoisCarte = null;
            this.refFacturationAnneeCarte = null;
            this.refFacturationNom = null;
            this.refFacturationPrenom = null;
            this.refFacturationAdresse = null;
            this.refFacturationVille = null;
            this.refFacturationProvince = null;
            this.refFacturationCodePostal = null;
            this.refMode = null;
            //---- Références des inputs (Connexion) -----
            this.refConnexionCourriel = null;
            this.refConnexionMdp = null;
            //---- Références des inputs (Créer un compte) -----
            this.refCreerPrenom = null;
            this.refCreerNom = null;
            this.refCreerTelephone = null;
            this.refCreerCourriel = null;
            this.refCreerMdp = null;
            //---- Attributs écouteurs d'événements -----
            this.validerChamp_lier = null;
            this.validerBoutonsRadios_lier = null;
            this.validerListe_lier = null;
            this.verifierMode_lier = null;
            this.messagesValidation = messagesValidation;
            this.initialiser();
        }
        // Initialisation des evenements et de la valdiation
        Validation.prototype.initialiser = function () {
            var conteneurPage = document.querySelector('main div');
            var conteneurLivraison = document.querySelector('.livraison');
            var conteneurFacturation = document.querySelector('.facturation');
            var conteneurFacturationAdresse = document.querySelector('.facturation__adresse--nouvelle');
            var conteneurConnexion = document.querySelector('.connexionClient');
            var conteneurCreer = document.querySelector('.creerClient');
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
                for (var intCpt = 0; intCpt < this.refFacturationModePaiement.length; intCpt++) {
                    this.refFacturationModePaiement[intCpt].addEventListener("blur", this.validerBoutonsRadios_lier);
                    this.refFacturationModePaiement[intCpt].addEventListener("blur", this.verifierMode_lier);
                }
                for (var intCpt = 0; intCpt < this.refFacturationCarteCredit.length; intCpt++) {
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
        };
        // Validation des input de type texte
        Validation.prototype.validerChamp = function (evenement) {
            var element = evenement.currentTarget;
            var motif = element.pattern;
            var valeurChamp = element.value;
            if (valeurChamp === '') {
                this.afficherErreur(element, 'vide');
            }
            else {
                var resultatMotif = this.verifierMotif(valeurChamp, motif);
                if (resultatMotif === true) {
                    this.afficherSucces(element);
                }
                else {
                    this.afficherErreur(element, 'motif');
                }
            }
        };
        // Validation des input de type slect
        Validation.prototype.validerListe = function (evenement) {
            var element = evenement.currentTarget;
            var valeurChamp = element.value;
            var conteneurFacturationCredit = document.querySelector('.facturation__informationsCredit .actif');
            if (valeurChamp == '') {
                this.afficherErreur(element, 'vide');
            }
            else {
                this.afficherSucces(element);
            }
            //Validation de la date d'expiration
            if (this.refMode == true) {
                if (this.refFacturationMoisCarte.value !== '' && this.refFacturationAnneeCarte.value !== '') {
                    if (this.validerDateExpiration() == true) {
                        this.afficherSucces(element);
                    }
                    else {
                        this.afficherErreur(element, 'expire');
                    }
                }
            }
        };
        // Validation de la date de carte de crédit pour vérifier l'expiration
        Validation.prototype.validerDateExpiration = function () {
            var dateSaisie = new Date(this.refFacturationAnneeCarte.value + '-' + this.refFacturationMoisCarte.value + '-01');
            var today = new Date();
            return dateSaisie.getTime() >= today.getTime();
        };
        // Validation des boutons radios
        Validation.prototype.validerBoutonsRadios = function (evenement) {
            var element = evenement.currentTarget;
            var conteneurFormulaire = element.closest('.ctnForm');
            var champValidation = conteneurFormulaire.querySelector('.validation__message');
            if (element.checked) {
                champValidation.innerHTML = '';
            }
            else {
                champValidation.innerHTML = "<svg class='formulaire__validation'>\n" + "<use xlink:href='#icone_form_erreur'>&nbsp;</use>\n" + "</svg>" + this.messagesValidation[element.name]['vide'];
            }
        };
        // Validation des patterns
        Validation.prototype.verifierMotif = function (valeurChamp, motif) {
            var regExp = new RegExp(motif);
            return regExp.test(valeurChamp);
        };
        // Méthode qui gère le succès d'un champs
        Validation.prototype.afficherSucces = function (element) {
            var conteneurFormulaire = element.closest('.ctnForm');
            var champValidation = conteneurFormulaire.querySelector('.validation__message');
            // Retire l'icone d'erreur s'il est présent
            champValidation.innerHTML = '';
            // Ajout de l'icone de succès
            champValidation.innerHTML = '<svg class="formulaire__validation formulaire__validation--succes">\n' + '<use xlink:href="#icone_form_valide"></use>\n' + '</svg>';
            champValidation.classList.add('validation__message--succes');
        };
        // Affichage de l'erreur et retrait de l'icone de succès
        Validation.prototype.afficherErreur = function (element, typeMessage) {
            var conteneurFormulaire = element.closest('.ctnForm');
            var champValidation = conteneurFormulaire.querySelector('.validation__message');
            // Ajout de l'icone d'erreur
            champValidation.classList.remove('validation__message--succes');
            champValidation.innerHTML = "<svg class='formulaire__validation'>\n" + "<use xlink:href='#icone_form_erreur'>&nbsp;</use>\n" + "</svg>" + this.messagesValidation[element.name][typeMessage];
        };
        // Valide le mode de paiement pour limiter la validation selon le choix
        Validation.prototype.validerMode = function (evenement) {
            var element = evenement.currentTarget;
            if (element.value == 'carteCredit') {
                this.refMode = true;
                this.initialiserCarte();
            }
            else {
                this.refMode = false;
            }
        };
        // Méthode d'initialisation des éléments pour les cartes de crédit
        Validation.prototype.initialiserCarte = function () {
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
        };
        return Validation;
    }());
    exports.Validation = Validation;
});
//# sourceMappingURL=Validation.js.map