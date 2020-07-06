@extends('gabarit')@section('contenu')

    <div class="conteneur connexionClient">
        <h1 class="h2">Se connecter</h1>

        <form action="index.php?controleur=client&action=valider" method="POST">
            <fieldset class="formulaire">
                <legend class="screen-reader-only">Se connecter</legend>
                @if(isset($arrClient['client']['message']))
                    <p class="retroaction__creationDeCompte" aria-atomic="true" aria-live="assertive">
                        <svg class="formulaire__validation"><use xlink:href="#icone_form_valide"/></svg> {{$arrClient['client']['message']}}</p>
                @endif

                 {{--Connexion - Champ du courriel --}}
                <div class="formulaire__courriel ctnForm">
                    <label for="courriel" class="formulaire__courrielLabel">Courriel</label>

                    <input type="email" name="courriel" id="courriel" required class="formulaire__courrielInput connexion__courriel"
                           pattern="^[a-zA-Z0-9][a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*[@][a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*[.][a-zA-Z]{2,}$"
                           @if($arrClient["courriel"]["valeur"] !== "") value="{{$arrClient["courriel"]["valeur"]}}" @endif>

                    {{--Validation serveur et client, affichage des icônes et messages--}}
                    <p class="formulaire__courrielErreur validation__message">
                        @if(isset($arrClient['courriel']['message']))
                            {{--Affichage du bon icone selon la validation--}}
                            @if(isset($arrClient["courriel"]["champValide"]))
                                @if($arrClient["courriel"]["champValide"] == 1)
                                    <svg class="formulaire__validation"><use xlink:href="#icone_form_valide"/></svg>
                                @else
                                    <svg class="formulaire__validation"><use xlink:href="#icone_form_erreur"/></svg>
                                @endif
                            @endif
                            {{$arrClient['courriel']['message']}}
                        @endif
                    </p>
                </div>

                 {{--Connexion - Mot de passe --}}
                <div class="formulaire__mdp ctnForm">
                    <label for="mot_de_passe" class="formulaire__mdpLabel">Mot de passe</label>

                    <input type="password" name="mot_de_passe" id="mot_de_passe" required
                           pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).[a-zA-Z0-9-_ ]{8,}"
                        class="formulaire__mdpInput connexion__mdp"
                           @if($arrClient["mot_de_passe"]["valeur"] !== "" && !isset($arrClient['client']['message']))
                                value="{{$arrClient["mot_de_passe"]["valeur"]}}"
                           @endif>

                    {{--Validation serveur et client, affichage des icônes et messages--}}
                    <p class="formulaire__mot_de_passeErreur validation__message" aria-atomic="true" aria-live="assertive">
                        @if(isset($arrClient['mot_de_passe']['message']))
                            {{$arrClient['mot_de_passe']['message']}}
                            {{--Affichage du bon icone selon la validation--}}
                            @if(isset($arrClient["mot_de_passe"]["champValide"]) && !isset($arrClient['client']['message']))
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
                <input id="connecter_compte" class="bouton--plein" type="submit" value="Se connecter">
            </div>

            <div class="formulaire__oubliMdp">
                <a href="#" class="lienAnime">Mot de passe oublié?
                    <svg class="formulaire__oubliMdpIcone icone">
                        <use xlink:href="#icone_fleche_droite"/>
                    </svg>
                </a>
            </div>

            {{--Lien vers la création d'un nouveau compte--}}
            <div class="formulaire__pasCompte">
                <span class="icone__aide"></span> <p>Vous n'avez pas de compte?</p>
                <a class="lienAnime" href="index.php?controleur=client&action=creer">
                    Se créer un compte
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