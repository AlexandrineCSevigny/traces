@extends('gabarit') @section('contenu')

    <div class="conteneur creerClient">
        <h1 class="h2">Créer un compte</h1>

        <form action="index.php?controleur=client&action=inserer" method="POST">

            <fieldset class="formulaire">
                <legend class="screen-reader-only">Créer un compte</legend>

                {{-- Créer un compte - Champ du nom et prénom --}}
                <div class="formulaire__prenom ctnForm">
                    <label for="prenom" class="formulaire__prenomLabel">Prénom</label>

                    <input type="text" name="prenom" id="prenom" required pattern="^[a-zA-ZÀ-ÿ' -]+$"
                           class="formulaire__prenomInput creer__prenom"
                    @if (isset($arrClient))
                           @if($arrClient["prenom"]["valeur"] !== "") value="{{$arrClient["prenom"]["valeur"]}}" @endif
                    @endif>

                    {{--Validation serveur et client, affichage des icônes et messages--}}
                    <p class="formulaire__prenomErreur validation__message" aria-atomic="true" aria-live="assertive">
                        @if(isset($arrClient['prenom']['message']))
                            {{$arrClient['prenom']['message']}}
                            @if(isset($arrClient["prenom"]["champValide"]))
                                @if($arrClient["prenom"]["champValide"] == 1)
                                    <svg class="formulaire__validation"><use xlink:href="#icone_form_valide"/></svg>
                                @else
                                    <svg class="formulaire__validation"><use xlink:href="#icone_form_erreur"/></svg>
                                @endif
                            @endif
                        @endif
                    </p>
                </div>

                <div class="formulaire__nom ctnForm">
                    <label for="nom" class="formulaire__nomLabel">Nom</label>

                    <input type="text" name="nom" id="nom" required pattern="^[a-zA-ZÀ-ÿ' -]+$"
                           class="formulaire__nomInput creer__nom"
                    @if (isset($arrClient))
                           @if($arrClient["nom"]["valeur"] !== "") value="{{$arrClient["nom"]["valeur"]}}" @endif
                    @endif>

                    {{--Validation serveur et client, affichage des icônes et messages--}}
                    <p class="formulaire__nomErreur validation__message" aria-atomic="true" aria-live="assertive">
                        @if(isset($arrClient['nom']['message']))
                            {{$arrClient['nom']['message']}}
                            {{--Affichage du bon icone selon la validation--}}
                            @if(isset($arrClient["nom"]["champValide"]))
                                @if($arrClient["nom"]["champValide"] == 1)
                                    <svg class="formulaire__validation"><use xlink:href="#icone_form_valide"/></svg>
                                @else
                                    <svg class="formulaire__validation"><use xlink:href="#icone_form_erreur"/></svg>
                                @endif
                            @endif
                        @endif
                    </p>
                </div>

                {{-- Créer un compte - Champ du téléphone --}}
                <div class="formulaire__telephone ctnForm">
                    <label for="telephone" class="formulaire__telephoneLabel">Téléphone</label>

                    <p class="messageInfo">Pas de parenthèses, espace(s) ou tiret(s), exemple : 4186592508</p>
                    <input type="text" name="telephone" id="telephone" required
                           pattern="^[0-9]{10}$"
                           class="input--moyen creer__telephone"
                    @if (isset($arrClient))
                           @if($arrClient["telephone"]["valeur"] !== "") value="{{$arrClient["telephone"]["valeur"]}}" @endif
                    @endif>

                    {{--Validation serveur et client, affichage des icônes et messages--}}
                    <p class="formulaire__telephoneErreur validation__message" aria-atomic="true" aria-live="assertive">
                        @if(isset($arrClient['telephone']['message']))
                            {{$arrClient['telephone']['message']}}
                            {{--Affichage du bon icone selon la validation--}}
                            @if(isset($arrClient["telephone"]["champValide"]))
                                @if($arrClient["telephone"]["champValide"] == 1)
                                    <svg class="formulaire__validation"><use xlink:href="#icone_form_valide"/></svg>
                                @else
                                    <svg class="formulaire__validation"><use xlink:href="#icone_form_erreur"/></svg>
                                @endif
                            @endif
                        @endif
                    </p>
                </div>

                {{-- Créer un compte - Champ du courriel --}}
                <div class="formulaire__courriel ctnForm">
                    <label for="courriel" class="formulaire__courrielLabel">Courriel</label>

                    <input type="email" name="courriel" id="courriel" required
                           pattern="^[a-zA-Z0-9][a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*[@][a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*[.][a-zA-Z]{2,}$"
                           class="formulaire__courrielInput courrielAjax creer__courriel"
                    @if (isset($arrClient))
                            @if($arrClient["courriel"]["valeur"] !== "") value="{{$arrClient["courriel"]["valeur"]}}" @endif
                    @endif>

                    {{--Validation serveur et client, affichage des icônes et messages--}}
                    <p class="formulaire__courrielErreur validation__message" aria-atomic="true" aria-live="assertive">
                        @if(isset($arrClient['courriel']['message']))
                            {{$arrClient['courriel']['message']}}
                            {{--Affichage du bon icone selon la validation--}}
                            @if(isset($arrClient["courriel"]["champValide"]))
                                @if($arrClient["courriel"]["champValide"] == 1)
                                    <svg class="formulaire__validation"><use xlink:href="#icone_form_valide"/></svg>
                                @else
                                    <svg class="formulaire__validation"><use xlink:href="#icone_form_erreur"/></svg>
                                @endif
                            @endif
                        @endif
                    </p>

                    <p class="formulaire__courrielDisponible erreur__message"></p>
                </div>

                {{-- Créer un compte - Mot de passe --}}
                <div class="formulaire__mdp ctnForm">
                    <label for="mot_de_passe" class="formulaire__mdpLabel">Mot de passe</label>
                    <p class="messageInfo">Saisir un minimum de 8 caractères, au moins une minuscule et une majuscule et un caractère numérique.</p>

                    <input type="password" name="mot_de_passe" id="mot_de_passe" required
                           pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).[a-zA-Z0-9-_ ]{8,}"
                           class="formulaire__mdpInput creer__mdp"
                    @if (isset($arrClient))
                            @if($arrClient["mot_de_passe"]["valeur"] !== "") value="{{$arrClient["mot_de_passe"]["valeur"]}}" @endif
                    @endif>

                    {{--Validation serveur et client, affichage des icônes et messages--}}
                    <p class="formulaire__mot_de_passeErreur validation__message" aria-atomic="true" aria-live="assertive">
                        @if(isset($arrClient['mot_de_passe']['message']))
                            {{$arrClient['mot_de_passe']['message']}}
                            {{--Affichage du bon icone selon la validation--}}
                            @if(isset($arrClient["mot_de_passe"]["champValide"]))
                                @if($arrClient["mot_de_passe"]["champValide"] == 1)
                                    <svg class="formulaire__validation"><use xlink:href="#icone_form_valide"/></svg>
                                @else
                                    <svg class="formulaire__validation"><use xlink:href="#icone_form_erreur"/></svg>
                                @endif
                            @endif
                        @endif
                    </p>

                    <div class="formulaire__mdpAfficher">
                        <input class="visuallyhidden afficherMdp" id="afficher_mdp" type="checkbox" value="afficher_mdp" aria-label="checkbox">
                        <label for="afficher_mdp">Afficher le mot de passe</label>
                    </div>
                </div>
            </fieldset>

            <div class="formulaire__bouton">
                <input id="creer_compte" class="bouton--plein" type="submit" value="Se créer un compte">
            </div>

            {{--Lien vers la création d'un nouveau compte --}}
            <div class="formulaire__pasCompte">
                <span class="icone__aide"></span> <p>Vous avez déjà un compte?</p>
                <a class="lienAnime" href="index.php?controleur=client&action=afficher">
                    Se connecter
                    <svg class="formulaire__pasCompteIcone icone">
                        <use xlink:href="#icone_fleche_droite"/>
                    </svg>
                </a>
            </div>

            {{--Lien vers la livraison directement--}}
            <div class="formulaire__sansCompte">
                <a href="index.php?controleur=livraison&action=afficher" class="bouton--vide">Acheter sans créer de compte</a>
            </div>

        </form>
    </div>
@endsection