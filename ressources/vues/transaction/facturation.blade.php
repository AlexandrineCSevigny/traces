@extends('gabarit')

@section('contenu')
    <div class="facturation conteneur transaction">
        <h1>Facturation</h1>

        <form action="index.php?controleur=facturation&action=valider" method="POST" class="section--espace">
            <fieldset class="formulaire section--espace">
                <legend class="visuallyhidden">Informations de paiement</legend>
                <h2>Informations de paiement</h2>

                <!-----------   Mode de paiment  ----------->
                <div class="ctnForm">
                    <p class="h4">Sélectionner un mode de paiement</p>
                    <ul class="facturation__modesPaiement">
                        <li class="facturation__modePaiement">
                            <input type="radio" id="paypal" name="modePaiement" value="paypal" required
                                   class="radioInput visuallyhidden"
                                   @if($arrFacturation['modePaiement']['valeur'] == 'paypal') checked @endif>
                            <label for="paypal" class="radioLabel focusable">Paypal</label>
                        </li>
                        <li class="facturation__modePaiement">
                            <input type="radio" id="carteCredit" name="modePaiement" value="carteCredit" required
                                   class="radioInput visuallyhidden"
                                   @if($arrFacturation['modePaiement']['valeur'] == 'carteCredit') checked @endif>
                            <label for="carteCredit" class="radioLabel focusable">Carte de crédit</label>
                        </li>
                    </ul>

                    <!----   Gestion des messages d'erreur  ---->
                    <span class="validation__message" aria-atomic="true" aria-live="assertive">
                            @isset($arrFacturation['modePaiement']['message'])
                            @isset($arrFacturation['modePaiement']["champValide"])
                                @if($arrFacturation['modePaiement']["champValide"] != 1)
                                    <svg class="formulaire__validation">
                                <use xlink:href="#icone_form_erreur"/>
                            </svg>
                                @endif
                            @endisset
                            {{$arrFacturation['modePaiement']['message']}}
                        @endisset
                        </span>
                </div>


                <section class="facturation__informationsCredit">
                    <!-----------   Carte de crédits  ----------->
                    <div class="ctnForm">
                        <p class="h4">Sélectionner votre type de carte de crédit</p>
                        <ul class="facturation__cartes">
                            <li class="facturation__carte">
                                <input type="radio" id="masterCard" name="cartesCredit" value="masterCard" required
                                       @if($arrFacturation['cartesCredit']['valeur'] == 'masterCard') checked
                                       @endif  class="screen-reader-only radioInput">
                                <label for="masterCard" class="radioLabel">
                                    <span class="facturation__carteLibelle">MasterCard</span>
                                    <svg class="icone facturation__icone">
                                        <use xlink:href="#icone_masterCard"/>
                                    </svg>
                                </label>
                            </li>
                            <li class="facturation__carte">
                                <input type="radio" id="visa" name="cartesCredit" value="visa" required
                                       @if($arrFacturation['cartesCredit']['valeur'] == 'visa') checked
                                       @endif  class="screen-reader-only radioInput">
                                <label for="visa" class="radioLabel">
                                    <span class="facturation__carteLibelle">Visa</span>
                                    <svg class="icone facturation__icone">
                                        <use xlink:href="#icone_visa"/>
                                    </svg>
                                </label>
                            </li>
                            <li class="facturation__carte">
                                <input type="radio" id="amex" name="cartesCredit" value="amex" required
                                       @if($arrFacturation['cartesCredit']['valeur'] == 'amex') checked
                                       @endif  class="screen-reader-only radioInput">
                                <label for="amex" class="radioLabel">
                                    <span class="facturation__carteLibelle">American Express</span>
                                    <svg class="icone facturation__icone">
                                        <use xlink:href="#icone_amex"/>
                                    </svg>
                                </label>
                            </li>
                        </ul>


                        <!----   Gestion des messages d'erreur  ---->
                        <span class="validation__message" aria-atomic="true" aria-live="assertive">
                             @isset($arrFacturation['cartesCredit']['message'])
                                @isset($arrFacturation['cartesCredit']["champValide"])
                                    @if($arrFacturation['cartesCredit']["champValide"] != 1)
                                        <svg class="formulaire__validation">
                                    <use xlink:href="#icone_form_erreur"/>
                                </svg>
                                    @endif
                                @endisset
                                {{$arrFacturation['cartesCredit']['message']}}
                            @endisset
                        </span>
                    </div>


                    <!-----------   Nom carte de crédit  ----------->
                    <div class="ctnForm">
                        <label for="nom">Titulaire de carte</label>
                        <input type="text" name="nomCarte" id="nom" required class="facturation__nomCarte"
                               @if(isset($arrFacturation))value="{{$arrFacturation['nomCarte']['valeur']}}" @endif
                               pattern="^[a-zA-ZÀ-ÿ\- ][a-zA-ZÀ-ÿ\- ]{1,}$"
                               title="Utilisez des lettres, des traits d'unions ou des espaces pour composer votre nom."
                               aria-required="true"/>

                        <!----   Gestion des messages d'erreur  ---->
                        <p class="validation__message" aria-atomic="true" aria-live="assertive">
                            @isset($arrFacturation['nomCarte']['message'])
                                @isset($arrFacturation['nomCarte']["champValide"])
                                    @if($arrFacturation['nomCarte']["champValide"] == 1)
                                        <svg class="formulaire__validation">
                                            <use xlink:href="#icone_form_valide"/>
                                        </svg>
                                    @else
                                        <svg class="formulaire__validation">
                                            <use xlink:href="#icone_form_erreur"/>
                                        </svg>
                                    @endif
                                @endisset
                                {{$arrFacturation['nomCarte']['message']}}
                            @endisset
                        </p>
                    </div>


                    <!-----------   Numéro carte de crédit  ----------->
                    <div class="ctnForm">
                        <label for="numeroCarte">Numéro de la carte</label>
                        <input type="text" name="numeroCarte" id="numeroCarte" required
                               pattern="^[0-9]{4}[ ]?[0-9]{4}[ ]?[0-9]{4}[ ]?[0-9]{4}$" class="facturation__numeroCarte"
                               title="Utilisez des chiffres seulement." maxlength="20"
                               aria-required="true"/>

                        <!----   Gestion des messages d'erreur  ---->
                        <p class="validation__message" aria-atomic="true" aria-live="assertive">
                            @isset($arrFacturation['numeroCarte']['message'])
                                @isset($arrFacturation['numeroCarte']["champValide"])
                                    @if($arrFacturation['numeroCarte']["champValide"] == 1)
                                        <svg class="formulaire__validation">
                                            <use xlink:href="#icone_form_valide"/>
                                        </svg>
                                    @else
                                        <svg class="formulaire__validation">
                                            <use xlink:href="#icone_form_erreur"/>
                                        </svg>
                                    @endif
                                @endisset
                                {{$arrFacturation['numeroCarte']['message']}}
                            @endisset
                        </p>
                    </div>


                    <!-----------   Code de sécurité  ----------->
                    <div class="ctnForm">
                        <label for="codeSecurite">Code de sécurité</label>
                        <input type="text" name="codeSecurite" id="codeSecurite" required
                               pattern="^[0-9]{3}$" maxlength="3"
                               title="Inscrire les trois chiffres indiqués au verso de votre carte."
                               aria-required="true" class="input--petit facturation__code"/>
                        <img alt="Information code de sécurité" src="liaisons/images/autres/icone_aide.png">

                        <!----   Gestion des messages d'erreur  ---->
                        <p class="validation__message" aria-atomic="true" aria-live="assertive">
                            @isset($arrFacturation['codeSecurite']['message'])
                                @isset($arrFacturation['codeSecurite']["champValide"])
                                    @if($arrFacturation['codeSecurite']["champValide"] == 1)
                                        <svg class="formulaire__validation">
                                            <use xlink:href="#icone_form_valide"/>
                                        </svg>
                                    @else
                                        <svg class="formulaire__validation">
                                            <use xlink:href="#icone_form_erreur"/>
                                        </svg>
                                    @endif
                                @endisset
                                {{$arrFacturation['codeSecurite']['message']}}
                            @endisset
                        </p>
                    </div>


                    <fieldset class="ctnForm">
                        <legend>Date d'expiration</legend>

                        <!-----------  Mois carte de crédit  ----------->
                        <label for="mois" class="visuallyhidden">Mois</label>
                        <div class="select__conteneur">
                            <select name="moisCarte" id="mois" required aria-required="true"
                                    class="facturation__moisCarte">
                                <option value="">Mois</option>
                                <option value="01" @if($arrFacturation['moisCarte']['valeur'] == '01') selected @endif>
                                    01 -
                                    Janvier
                                </option>
                                <option value="02" @if($arrFacturation['moisCarte']['valeur'] == '02') selected @endif>
                                    02 -
                                    Février
                                </option>
                                <option value="03" @if($arrFacturation['moisCarte']['valeur'] == '03') selected @endif>
                                    03 -
                                    Mars
                                </option>
                                <option value="04" @if($arrFacturation['moisCarte']['valeur'] == '04') selected @endif>
                                    04 -
                                    Avril
                                </option>
                                <option value="05" @if($arrFacturation['moisCarte']['valeur'] == '05') selected @endif>
                                    05 -
                                    Mai
                                </option>
                                <option value="06" @if($arrFacturation['moisCarte']['valeur'] == '06') selected @endif>
                                    06 -
                                    Juin
                                </option>
                                <option value="07" @if($arrFacturation['moisCarte']['valeur'] == '07') selected @endif>
                                    07 -
                                    Juillet
                                </option>
                                <option value="08" @if($arrFacturation['moisCarte']['valeur'] == '08') selected @endif>
                                    08 -
                                    Août
                                </option>
                                <option value="09" @if($arrFacturation['moisCarte']['valeur'] == '09') selected @endif>
                                    09 -
                                    Septembre
                                </option>
                                <option value="10" @if($arrFacturation['moisCarte']['valeur'] == '10') selected @endif>
                                    10 -
                                    Octobre
                                </option>
                                <option value="11" @if($arrFacturation['moisCarte']['valeur'] == '11') selected @endif>
                                    11 -
                                    Novembre
                                </option>
                                <option value="12" @if($arrFacturation['moisCarte']['valeur'] == '12') selected @endif>
                                    12 -
                                    Décembre
                                </option>
                            </select>
                        </div>

                        <!-----------   Année carte de crédit  ----------->

                        <label for="annee" class="visuallyhidden">Année</label>
                        <div class="select__conteneur">
                            <select name="anneeCarte" id="annee" required aria-required="true"
                                    class="facturation__anneeCarte">
                                <option value="">Année</option>
                                <option value="2019"
                                        @if($arrFacturation['anneeCarte']['valeur'] == '2019') selected @endif>
                                    2019
                                </option>
                                <option value="2020"
                                        @if($arrFacturation['anneeCarte']['valeur'] == '2020') selected @endif>
                                    2020
                                </option>
                                <option value="2021"
                                        @if($arrFacturation['anneeCarte']['valeur'] == '2021') selected @endif>
                                    2021
                                </option>
                                <option value="2022"
                                        @if($arrFacturation['anneeCarte']['valeur'] == '2022') selected @endif>
                                    2022
                                </option>
                                <option value="2023"
                                        @if($arrFacturation['anneeCarte']['valeur'] == '2023') selected @endif>
                                    2023
                                </option>
                                <option value="2024"
                                        @if($arrFacturation['anneeCarte']['valeur'] == '2024') selected @endif>
                                    2024
                                </option>
                                <option value="2025"
                                        @if($arrFacturation['anneeCarte']['valeur'] == '2025') selected @endif>
                                    2025
                                </option>
                            </select>
                        </div>


                        <!----   Gestion des messages d'erreur  ---->
                        <p class="validation__message" aria-atomic="true" aria-live="assertive">
                            @if(isset($arrFacturation['moisCarte']['message']))
                                @isset($arrFacturation['moisCarte']["champValide"])
                                    @if($arrFacturation['moisCarte']["champValide"] == 1 && $arrFacturation['anneeCarte']["champValide"] == 1 && $arrFacturation['expirationCarte']["champValide"] == 1)
                                        <svg class="formulaire__validation">
                                            <use xlink:href="#icone_form_valide"/>
                                        </svg>
                                    @else
                                        <svg class="formulaire__validation">
                                            <use xlink:href="#icone_form_erreur"/>
                                        </svg>
                                    @endif
                                @endisset

                                {{$arrFacturation['moisCarte']['message']}}
                            @endif
                            @if(isset($arrFacturation['anneeCarte']['message']))
                                @isset($arrFacturation['moisCarte']["champValide"])
                                    @if($arrFacturation['moisCarte']["champValide"] == 1 && $arrFacturation['anneeCarte']["champValide"] == 1 && $arrFacturation['expirationCarte']["champValide"] == 1)
                                        <svg class="formulaire__validation">
                                            <use xlink:href="#icone_form_valide"/>
                                        </svg>
                                    @else
                                        <svg class="formulaire__validation">
                                            <use xlink:href="#icone_form_erreur"/>
                                        </svg>
                                    @endif
                                @endisset
                                {{$arrFacturation['anneeCarte']['message']}}
                            @endif
                            @if(isset($arrFacturation['expirationCarte']['message']))
                                @isset($arrFacturation['moisCarte']["champValide"])
                                    @if($arrFacturation['moisCarte']["champValide"] == 1 && $arrFacturation['anneeCarte']["champValide"] == 1 && $arrFacturation['expirationCarte']["champValide"] == 1)
                                        <svg class="formulaire__validation">
                                            <use xlink:href="#icone_form_valide"/>
                                        </svg>
                                    @else
                                        <svg class="formulaire__validation">
                                            <use xlink:href="#icone_form_erreur"/>
                                        </svg>
                                    @endif
                                @endisset
                                {{$arrFacturation['expirationCarte']['message']}}
                            @endif
                        </p>
                    </fieldset>
                </section>
            </fieldset>


            <fieldset class="facturation__adresse formulaire section--espace">
                <legend class="visuallyhidden">Adresse de facturation</legend>
                <h2>Adresse de facturation</h2>

                @if(isset($arrLivraison) && ($arrLivraison['adresseFacturation']['valeur'] == 'on'))
                    @include('transaction.fragments.facturationPreRempli')

                @else
                    <div class="facturation__adresse--nouvelle">
                        <fieldset>
                            <legend class="visuallyhidden">Individu</legend>

                            <!-----------   Prénom  ----------->
                            <div class="ctnForm">

                                <label for="prenom">Prénom</label>
                                <input type="text" name="prenom" id="prenom" required class="facturation__prenom"
                                       @if(isset($arrFacturation))value="{{$arrFacturation['prenom']['valeur']}}" @endif
                                       pattern="[a-zA-ZÀ-ÿ' -]+$"
                                       title="Utilisez des lettres, des traits d'unions ou des espaces pour composer votre prénom."
                                       aria-required="true"/>

                                <!----   Gestion des messages d'erreur  ---->
                                <p class="validation__message" aria-atomic="true" aria-live="assertive">
                                    @isset($arrFacturation['prenom']['message'])
                                        @isset($arrFacturation['prenom']["champValide"])
                                            @if($arrFacturation['prenom']["champValide"] == 1)
                                                <svg class="formulaire__validation">
                                                    <use xlink:href="#icone_form_valide"/>
                                                </svg>
                                            @else
                                                <svg class="formulaire__validation">
                                                    <use xlink:href="#icone_form_erreur"/>
                                                </svg>
                                            @endif
                                        @endisset
                                        {{$arrFacturation['prenom']['message']}}
                                    @endisset
                                </p>
                            </div>

                            <!-----------   Nom  ----------->
                            <div class="ctnForm">
                                <label for="nom">Nom</label>
                                <input type="text" name="nom" id="nom" required class="facturation__nom"
                                       pattern="^[a-zA-ZÀ-ÿ' -]+$"
                                       title="Utilisez des lettres, des traits d'unions ou des espaces pour composer votre nom."
                                       aria-required="true"
                                       @if(isset($arrFacturation))value="{{$arrFacturation['nom']['valeur']}}" @endif />


                                <!----   Gestion des messages d'erreur  ---->
                                <p class="validation__message" aria-atomic="true" aria-live="assertive">
                                    @isset($arrFacturation['nom']['message'])
                                        @isset($arrFacturation['nom']["champValide"])
                                            @if($arrFacturation['nom']["champValide"] == 1)
                                                <svg class="formulaire__validation">
                                                    <use xlink:href="#icone_form_valide"/>
                                                </svg>
                                            @else
                                                <svg class="formulaire__validation">
                                                    <use xlink:href="#icone_form_erreur"/>
                                                </svg>
                                            @endif
                                        @endisset
                                        {{$arrFacturation['nom']['message']}}
                                    @endisset
                                </p>
                            </div>
                        </fieldset>

                        <!-----------   Adresse ----------->
                        <div class="ctnForm">
                            <label for="adresse">Adresse</label>
                            <input type="text" name="adresse" id="adresse" required class="facturation__adresse"
                                   @if(isset($arrFacturation))value="{{$arrFacturation['adresse']['valeur']}}" @endif
                                   pattern="^[0-9]{1,6} [- 'a-zA-ZÀ-ÿ0-9]+$"
                                   title="Utilisez des lettres, des chiffres, des traits d'unions ou des espaces pour composer votre adresse."
                                   aria-required="true"/>


                            <!----   Gestion des messages d'erreur  ---->
                            <p class="validation__message" aria-atomic="true" aria-live="assertive">
                                @isset($arrFacturation['adresse']['message'])
                                    @isset($arrFacturation['adresse']["champValide"])
                                        @if($arrFacturation['adresse']["champValide"] == 1)
                                            <svg class="formulaire__validation">
                                                <use xlink:href="#icone_form_valide"/>
                                            </svg>
                                        @else
                                            <svg class="formulaire__validation">
                                                <use xlink:href="#icone_form_erreur"/>
                                            </svg>
                                        @endif
                                    @endisset
                                    {{$arrFacturation['adresse']['message']}}
                                @endisset
                            </p>
                        </div>


                        <!-----------  Ville ----------->
                        <div class="ctnForm">
                            <label for="ville">Ville</label>
                            <input type="text" name="ville" id="ville" required class="facturation__ville"
                                   @if(isset($arrFacturation))value="{{$arrFacturation['ville']['valeur']}}" @endif
                                   pattern="^[a-zA-ZÀ-ÿ' -]+$"
                                   title="Utilisez des lettres ou des espaces pour composer votre ville."
                                   aria-required="true"/>


                            <!----   Gestion des messages d'erreur  ---->
                            <p class="validation__message" aria-atomic="true" aria-live="assertive">
                                @isset($arrFacturation['ville']['message'])
                                    @isset($arrFacturation['ville']["champValide"])
                                        @if($arrFacturation['ville']["champValide"] == 1)
                                            <svg class="formulaire__validation">
                                                <use xlink:href="#icone_form_valide"/>
                                            </svg>
                                        @else
                                            <svg class="formulaire__validation">
                                                <use xlink:href="#icone_form_erreur"/>
                                            </svg>
                                        @endif
                                    @endisset
                                    {{$arrFacturation['ville']['message']}}
                                @endisset
                            </p>
                        </div>

                        <!-----------   Province  ----------->
                        <div class="ctnForm">
                            <label for="province">Province</label>
                            <div class="select__conteneur">
                                <select name="province" id="province" required aria-required="true"
                                        class="facturation__province">
                                    <option value="">Sélectionner une province</option>
                                    <option value="AB"
                                            @if($arrFacturation['province']['valeur'] == 'AB') selected @endif>
                                        Alberta
                                    </option>
                                    <option value="CB"
                                            @if($arrFacturation['province']['valeur'] == 'CB') selected @endif>
                                        Colombie-Britannique
                                    </option>
                                    <option value="PE"
                                            @if($arrFacturation['province']['valeur'] == 'PE') selected @endif>
                                        Île-du-Prince-Édouard
                                    </option>
                                    <option value="MB"
                                            @if($arrFacturation['province']['valeur'] == 'MB') selected @endif>
                                        Manitoba
                                    </option>
                                    <option value="NB"
                                            @if($arrFacturation['province']['valeur'] == 'NB') selected @endif>
                                        Nouveau-Brunswick
                                    </option>
                                    <option value="NS"
                                            @if($arrFacturation['province']['valeur'] == 'NS') selected @endif>
                                        Nouvelle-Écosse
                                    </option>
                                    <option value="ON"
                                            @if($arrFacturation['province']['valeur'] == 'ON') selected @endif>
                                        Ontario
                                    </option>
                                    <option value="QC"
                                            @if($arrFacturation['province']['valeur'] == 'QC') selected @endif>
                                        Québec
                                    </option>
                                    <option value="SK"
                                            @if($arrFacturation['province']['valeur'] == 'SK') selected @endif>
                                        Saskatchewan
                                    </option>
                                    <option value="NL"
                                            @if($arrFacturation['province']['valeur'] == 'NL') selected @endif>
                                        Terre-Neuve et Labrador
                                    </option>
                                    <option value="NT"
                                            @if($arrFacturation['province']['valeur'] == 'NT') selected @endif>
                                        Territoires du Nord-Ouest et Nunavut
                                    </option>
                                    <option value="YT"
                                            @if($arrFacturation['province']['valeur'] == 'YT') selected @endif>
                                        Yukon
                                    </option>
                                </select>
                            </div>


                            <!----   Gestion des messages d'erreur  ---->
                            <p class="validation__message" aria-atomic="true" aria-live="assertive">
                                @isset($arrFacturation['province']['message'])
                                    @isset($arrFacturation['province']["champValide"])
                                        @if($arrFacturation['province']["champValide"] == 1)
                                            <svg class="formulaire__validation">
                                                <use xlink:href="#icone_form_valide"/>
                                            </svg>
                                        @else
                                            <svg class="formulaire__validation">
                                                <use xlink:href="#icone_form_erreur"/>
                                            </svg>
                                        @endif
                                    @endisset
                                    {{$arrFacturation['province']['message']}}
                                @endisset
                            </p>
                        </div>


                        <!-----------   Code postal  ----------->
                        <div class="ctnForm">
                            <label for="codePostal"><span class="h4">Code postal</span>
                                (A0A 0A0)</label>
                            <input type="text" name="codePostal" id="codePostal" required aria-required="true"
                                   class="input--moyen facturation__codePostal"
                                   @if(isset($arrFacturation))value="{{$arrFacturation['codePostal']['valeur']}}" @endif
                                   pattern="^[A-Za-z][0-9][A-Za-z] [0-9][A-Za-z][0-9]$" title="A0A 0A0" maxlength="8">


                            <!----   Gestion des messages d'erreur  ---->
                            <p class="validation__message" aria-atomic="true" aria-live="assertive">
                                @isset($arrFacturation['codePostal']['message'])
                                    @isset($arrFacturation['codePostal']["champValide"])
                                        @if($arrFacturation['codePostal']["champValide"] == 1)
                                            <svg class="formulaire__validation">
                                                <use xlink:href="#icone_form_valide"/>
                                            </svg>
                                        @else
                                            <svg class="formulaire__validation">
                                                <use xlink:href="#icone_form_erreur"/>
                                            </svg>
                                        @endif
                                    @endisset
                                    {{$arrFacturation['codePostal']['message']}}
                                @endisset
                            </p>
                        </div>
                    </div>
            </fieldset>
            @endif

            <button type="submit" formnovalidate name="valider" class="bouton--vide" id="bouton__facturation"
                    value="valider">
                Valider la commande
            </button>
        </form>
    </div>
@endsection