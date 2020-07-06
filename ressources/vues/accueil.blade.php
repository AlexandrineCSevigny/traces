@extends('gabarit')

@section('contenu')

    <div class="accueil conteneur">
        <h1 class="screen-reader-only">Accueil de Traces</h1>
        <div class="coupDeCoeur conteneur--blanc">
            <div class="coupDeCoeur__entete">
                <h2 class="coupDeCoeur__enteteTitre">Coup de c&#339;ur</h2>
                <a href="#" class="coupDeCoeur__enteteVoirTous btn bouton__ligne--bleu">
                    <span>Voir tous nos coups de c&#339;ur</span>
                    <svg class="coupDeCoeur__icone icone">
                        <use xlink:href="#icone_fleche_droite"/>
                    </svg>
                </a>
                <div class="coupDeCoeur__enteteIcone">
                    <img src="liaisons/images/autres/illustration_coup_de_coeur.svg"
                         alt="Illustration Coup de coeur" class="coupDeCoeur__icone--illustration">
                </div>
            </div>

            <div class="coupDeCoeur__livres">
             @foreach ($coupsdecoeur as $coupdecoeur)
                <div class="coupDeCoeur__livre">
                    @if(!isset($_SESSION['preferences']['categorie']))
                        <a class="lienAnime" href="index.php?controleur=livre&action=fiche&isbn={{$coupdecoeur->isbn}}&categorie=catalogue">
                    @else
                        <a class="lienAnime" href="index.php?controleur=livre&action=fiche&isbn={{$coupdecoeur->isbn}}"> @endif

                 {{--Si l'imagine associé au roman existe, on l'affiche - sinon on met le placeholder!--}}
                    @if( $coupdecoeur->formaterIsbn() !== "placeholder")
                        <div class="coupDeCoeur__livreConteneur">
                            <picture class="coupDeCoeur__livreConteneurPicture">
                                <source media="(max-width:600px)" srcset="liaisons/images/couvertures_livres/{{$coupdecoeur->formaterIsbn()}}.jpg 1x, liaisons/images/couvertures_livres/{{$coupdecoeur->formaterIsbn()}}.jpg 2x">
                                <source media="(min-width:601px)" srcset="liaisons/images/couvertures_livres/{{$coupdecoeur->formaterIsbn()}}.jpg 1x, liaisons/images/couvertures_livres/{{$coupdecoeur->formaterIsbn()}}.jpg 2x">
                                <img src="liaisons/images/couvertures_livres/{{$coupdecoeur->formaterIsbn()}}.jpg"
                                     alt="{{$coupdecoeur->formaterTitre()}} - @foreach ($coupdecoeur->getAuteurs() as $auteur) {{$auteur->getNomPrenom()}}@endforeach"
                                     class="coupDeCoeur__livreImage">
                            </picture>
                            <div class="coupDeCoeur__livreConteneurLigne"></div>
                        </div>
                            <h3 class="coupDeCoeur__livreTitre">{{$coupdecoeur->formaterTitre()}}</h3>
                            <p class="coupDeCoeur__livreAuteur">
                                @foreach ($coupdecoeur->getAuteurs() as $auteur)
                                    @if($loop->last)
                                        <span>{{$auteur->getNomPrenom()}}</span>
                                    @else
                                        <span>{{$auteur->getNomPrenom()}}, </span>
                                    @endif
                                @endforeach
                            </p>
                            @else
                                <img src="liaisons/images/autres/couverture_placeholder.svg"
                                     alt="{{$coupdecoeur->formaterTitre()}} - Couverture non-disponible" class="coupDeCoeur__imageImg">
                    @endif </a></a>
                </div>
               @endforeach
            </div>
        </div>

        <div class="nouveautes conteneur--nouveaute">
            <div class="nouveautes__entete">
                <div>
                    <h2 class="nouveautes__enteteTitre">Nouveautés</h2>
                    <a href="index.php?controleur=livre&action=index&nouveau=nouveau"
                       class="nouveautes__enteteVoirToutes btn bouton__ligne--blanc">
                        <svg class="nouveautes__enteteIcone icone">
                            <use xlink:href="#icone_fleche_gauche"/>
                        </svg>
                        <span>Voir toutes nos nouveautés</span>
                    </a>
                </div>
            </div>

            <div class="nouveautes__livres">
                @foreach ($nouveautes as $nouveaute)
                <div class="nouveautes__livre">
                    <div class="nouveautes__icone"></div>

                    @if(!isset($_SESSION['preferences']['categorie']))
                        <a class="lienAnime" href="index.php?controleur=livre&action=fiche&isbn={{$nouveaute->isbn}}&categorie=nouveau">
                    @else
                        <a class="lienAnime" href="index.php?controleur=livre&action=fiche&isbn={{$nouveaute->isbn}}"> @endif

                     {{--Si l'imagine associé au roman existe, on l'affiche, sinon on met le placeholder!--}}
                        @if( $nouveaute->formaterIsbn() !== "placeholder")
                            <picture class="nouveautes__livrePicture">
                                <source media="(max-width:600px)" srcset="liaisons/images/couvertures_livres/{{$nouveaute->formaterIsbn()}}__235.jpg 1x, liaisons/images/couvertures_livres/{{$nouveaute->formaterIsbn()}}__320.jpg 2x">
                                <source media="(min-width:601px)" srcset="liaisons/images/couvertures_livres/{{$nouveaute->formaterIsbn()}}__235.jpg 1x, liaisons/images/couvertures_livres/{{$nouveaute->formaterIsbn()}}__320.jpg 2x">
                                 <img src="liaisons/images/couvertures_livres/{{$nouveaute->formaterIsbn()}}__235.jpg"
                                      alt="{{$nouveaute->formaterTitre()}}" class="nouveautes__livreImage">
                                <div class="nouveautes__livreLigne"></div>
                            </picture>
                        @else
                            <img src="liaisons/images/autres/couverture_placeholder.svg"
                                alt="{{$nouveaute->formaterTitre()}} - Couverture non-disponible" class="nouveautes__imageImg"/>
                                <div class="nouveautes__livreLigne"></div>
                         @endif

                         <h3 class="nouveautes__livreTitre">{{$nouveaute->formaterTitre()}}</h3>
                         <p class="nouveautes__livreAuteur">
                           @foreach ($nouveaute->getAuteurs() as $auteur)
                               @if($loop->last)
                                   <span>{{$auteur->getNomPrenom()}}</span>
                               @else
                                   <span>{{$auteur->getNomPrenom()}}, </span>
                                 @endif
                           @endforeach</p>
                         <p class="nouveautes__livrePrix">{{$nouveaute->formaterPrix()}}$</p>
                        </a></a>
                </div>
                @endforeach
            </div>
        </div>

        <div class="actualites">
            <div class="actualites__entete">
                <h2 class="actualites__enteteTitre">Actualités littéraires</h2>
                <a href="#" class="actualites__enteteVoirToutes btn bouton__ligne--bleu">
                    <span>Voir toutes nos actualités</span>
                    <svg class="actualites__icone icone">
                        <use xlink:href="#icone_fleche_droite"/>
                    </svg>
                </a>
                <div class="actualites__enteteIcone">
                    <img src="liaisons/images/autres/illustration_actualites.svg"
                         alt="Illustration Actualités" class="actualites__icone--illustration">
                </div>
            </div>
            <div class="actualites__bloc">
                @foreach ($actualites as $actualite)
                    <div class="actualites__blocConteneur">
                    <article class="actualite">
                        <header>
                            <time datetime=""
                                  class="actualite__date">{{$actualite->formaterDate($actualite->date)}}</time>
                            <h3 class="actualite__titre"><a href="#" class="lienAnime">{{$actualite->titre}}</a></h3>
                            <p class="actualite__auteur">
                                Par
                                {{--If pour trouver les auteurs des publications selon le id mis dans la BD--}}
                                @if ($actualite->id_auteur === "12")Jacques Bélanger @endif
                                @if ($actualite->id_auteur === "117")Francine Lalonde @endif
                                @if ($actualite->id_auteur === "196")Denis Vaugeois @endif
                                @if ($actualite->id_auteur === "312")Gilles Herman @endif
                                @if ($actualite->id_auteur === "368")Sophie Imbeault @endif
                            </p>
                        </header>

                        <p class="actualite__texte">
                            {{--Visualisation différente pour afficher les articles, avec leurs balises <b> et <i>--}}
                            {!!$actualite->raccourcirActualites($actualite->texte_actualite)!!}
                        </p>
                        <footer>
                            <picture class="actualite__picture">
                                <source media="(max-width:600px)" srcset="liaisons/images/auteurs/auteur_{{$actualite->id_auteur}}.jpg 1x, liaisons/images/auteurs/auteur_{{$actualite->id_auteur}}.jpg 2x">
                                <source media="(min-width:601px)" srcset="liaisons/images/auteurs/auteur_{{$actualite->id_auteur}}.jpg 1x, liaisons/images/auteurs/auteur_{{$actualite->id_auteur}}.jpg 2x">
                                <img src="liaisons/images/auteurs/auteur_{{$actualite->id_auteur}}.jpg"
                                     alt="@if ($actualite->id_auteur === "12")Jacques Bélanger @endif
                                     @if ($actualite->id_auteur === "117")Francine Lalonde @endif
                                     @if ($actualite->id_auteur === "196")Denis Vaugeois @endif
                                     @if ($actualite->id_auteur === "312")Gilles Herman @endif
                                     @if ($actualite->id_auteur === "368")Sophie Imbeault @endif - {{$actualite->titre}}"
                                     class="actualite__pictureImage">
                            </picture>
                            <a href="#" class="actualite__LireSuite btn bouton__plein--rose">
                                <span>Lire la suite</span> <span class="screen-reader-only">de l'article {{$actualite->titre}}</span>
                                <svg class="actualite__icone icone">
                                    <use xlink:href="#icone_fleche_droite"/>
                                </svg>
                            </a>
                        </footer>
                    </article>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection