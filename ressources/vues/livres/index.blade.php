@extends('gabarit')

@section('contenu')
    <div class="catalogue conteneur">

            {{----------------------  Filtres catégories  -----------------------}}
            <aside class="categories categories--table" role="complementary">
                <h2 class="h3 categories__titre">Catégories</h2>
                <ul>
                    <li class="categories__item"><a
                                class="categories__itemCategorie lienAnime @if(!isset($_GET['categorie']))categories__itemCategorie--enCours @endif"
                                href="index.php?controleur=livre&action=index">Toutes les
                            catégories</a></li>

                    @foreach ($categories as $categorie)
                        <li class="categories__item">
                            <a class="categories__itemCategorie lienAnime @if(isset($_GET['categorie']) && $_GET['categorie']==$categorie->id)categories__itemCategorie--enCours @endif"
                               href="index.php?controleur=livre&action=index&categorie={{$categorie->id}}">{{$categorie->nom_fr}}</a>
                        </li>
                    @endforeach
                </ul>
            </aside>

            <div class="catalogue__contenu">

                {{---------------------------  Fil d'ariane  -------------------------}}
                <div class="filAriane">
                    <div>
                        @include('fragments.filariane')
                    </div>
                </div>

                <h1 class="catalogue__titre">Catalogue</h1>


                {{------  Filtre catégories  mobile  ------}}
                <form action="index.php?controleur=livre&action=index" method="GET" class="categories--mobile"
                      role="form">
                    <input type="hidden" name="controleur" value="livre"/>
                    <input type="hidden" name="action" value="index"/>

                    <div class="catalogue__listeConteneur catalogue__listeConteneur--grand">
                        <label for="listeCategories" class="visuallyhidden">Filtrer par catégorie</label>
                        <span class="catalogue__listeLibelle">Filtrer par catégories</span>
                        <select id="listeCategories" name="categorie" class="catalogue__listeOption">
                            <option value="catalogue">Tout</option>
                            @foreach ($categories as $categorie)
                                <option value="{{$categorie->id}}"
                                        @isset($_GET['categorie'])@if($_GET['categorie'] == $categorie->id)selected @endif @endisset>{{\App\Utilitaires::raccourcirTexte($categorie->nom_fr, 1)}}</option>
                            @endforeach
                        </select>
                    </div>

                    <button class="catalogue__bouton">Filtrer</button>
                </form>

                {{-------------------------  Tris et mode d'affichage  -------------------------}}
                <div class="catalogue__preferences">

                    {{----------   Tris  -----------}}
                    <div class="catalogue__preferencesListes">

                        {{------  Tris titre & prix ------}}
                        @if(@isset($_GET['categorie']))
                            <form action="index.php?controleur=livre&action=index&categorie={{$categorieEnCours->id}}&js=inactif"
                                  method="POST" class="catalogue__preferencesTris" role="form">
                                @elseif(@isset($_GET['nouveau']))
                                    <form action="index.php?controleur=livre&action=index&nouveau=nouveau&js=inactif"
                                          method="POST" class="catalogue__preferencesTris" role="form">
                                        @else
                                            <form action="index.php?controleur=livre&action=index" method="POST"
                                                  class="catalogue__preferencesTris" role="form">
                                                @endif

                                                <div class="catalogue__listeConteneur catalogue__listeConteneur--petit">
                                                    <label for="tri" class="visuallyhidden">Trier les résultats
                                                        de</label>
                                                    <span class="catalogue__listeLibelle">Trier de</span>
                                                    <select id="tri" class="catalogue__listeOption tri" name="tri">
                                                        <option value="a-z"
                                                                @if($_SESSION['preferences']['tri'] == 'titre' && $_SESSION['preferences']['ordre'] == 'ASC') selected @endif>
                                                            A à Z
                                                        </option>
                                                        <option value="z-a"
                                                                @if($_SESSION['preferences']['tri'] == 'titre' && $_SESSION['preferences']['ordre'] == 'DESC') selected @endif>
                                                            Z à A
                                                        </option>
                                                        <option value="$-$$$"
                                                                @if($_SESSION['preferences']['tri'] == 'prix' && $_SESSION['preferences']['ordre'] == 'ASC') selected @endif>
                                                            $
                                                            à $$$
                                                        </option>
                                                        <option value="$$$-$"
                                                                @if($_SESSION['preferences']['tri'] == 'prix' && $_SESSION['preferences']['ordre'] == 'DESC') selected @endif>
                                                            $$$ à $
                                                        </option>
                                                    </select>
                                                </div>

                                                <button class="catalogue__bouton">Trier</button>
                                            </form>
                                    </form>
                            </form>

                            {{------   Quantité de livres par page  ------}}
                            @if(@isset($_GET['categorie']))
                                <form action="index.php?controleur=livre&action=index&categorie={{$categorieEnCours->id}}"
                                      method="POST" class="catalogue__preferencesQuantite" role="form">
                                    @else
                                        <form action="index.php?controleur=livre&action=index" method="POST"
                                              class="catalogue__preferencesQuantite" role="form">
                                            @endif

                                            <div class="catalogue__listeConteneur catalogue__listeConteneur--petit">
                                                <label for="quantite" class="visuallyhidden">Quantité de livres à
                                                    afficher
                                                    par
                                                    page</label>
                                                <span class="catalogue__listeLibelle">Items/page</span>
                                                <select id="quantite"
                                                        class="catalogue__quantite catalogue__listeOption"
                                                        name="quantite">
                                                    <option value="12"
                                                            @if($_SESSION['preferences']['quantite'] == '12') selected @endif>
                                                        12
                                                    </option>
                                                    <option value="20"
                                                            @if($_SESSION['preferences']['quantite'] == '20') selected @endif>
                                                        20
                                                    </option>
                                                    <option value="32"
                                                            @if($_SESSION['preferences']['quantite'] == '32') selected @endif>
                                                        32
                                                    </option>
                                                    <option value="0"
                                                            @if($_SESSION['preferences']['quantite'] == '0') selected @endif>
                                                        Tout
                                                        voir
                                                    </option>
                                                </select>
                                            </div>

                                            <button class="catalogue__bouton">Afficher</button>
                                        </form>
                                </form>


                                {{------   Mode d'affichage  ------}}
                                <div class="catalogue__preferencesAffichage">
                                    <div class="catalogue__affichageBouton">
                                        <span class="icone icone-grille"><span class="visuallyhidden">Afficher les résultats en mode liste</span></span>
                                        <span class="icone icone-liste"><span class="visuallyhidden">Afficher les résultats en mode grille</span></span>
                                    </div>
                                </div>
                    </div>

                    <p class="catalogue__preferencesResultat">{{$nombreLivresFiltres}} résultats
                        de {{$nombreTotalLivres}}</p>
                </div>

                {{----------------------  Affichage des livres  -----------------------}}
                <section class="cartes">
                    <div class="cartes--grille">
                        @foreach ($livres as $livre)
                            <article class="carte @if($livre->est_coup_de_coeur == 1) carte--coupDeCoeur @endif">
                                <a href="index.php?controleur=livre&action=fiche&isbn={{$livre->isbn}}">
                                    @if($livre->est_coup_de_coeur == 1)
                                        <p class="carte__titre--coupDeCoeur h3">Coup de coeur</p>
                                    @endif

                                    <div class="carte__image--bg">

                                        {{------   Nouveauté  ------}}
                                        @if($livre->getParution()->etat == 'Nouveauté')
                                            <span class="carte__parution">{{$livre->getParution()->etat}} </span>
                                        @endif

                                        {{------   Image  ------}}
                                        @if( $livre->formaterIsbn() !== "placeholder")
                                            <picture
                                                    class="carte__image @if($livre->getParution()->etat == 'Nouveauté') carte__image--nouveaute @endif">
                                                <source media="(max-width:600px)"
                                                        srcset="liaisons/images/couvertures_livres/{{$livre->formaterIsbn()}}__150.jpg 1x,
                                        liaisons/images/couvertures_livres/{{$livre->formaterIsbn()}}__320.jpg 2x">
                                                <source media="(min-width:601px)"
                                                        srcset="liaisons/images/couvertures_livres/{{$livre->formaterIsbn()}}__150.jpg 1x,
                                        liaisons/images/couvertures_livres/{{$livre->formaterIsbn()}}__320.jpg 2x">
                                                <img src="liaisons/images/couvertures_livres/{{$livre->formaterIsbn()}}__150.jpg"
                                                     alt="{{$livre->formaterTitre()}} - @foreach ($livre->getAuteurs() as $auteur) {{$auteur->getNomPrenom()}}@endforeach"
                                                     class="carte__image @if($livre->getParution()->etat == 'Nouveauté') carte__image--nouveaute @endif">
                                            </picture>
                                        @else
                                            <img src="liaisons/images/autres/couverture_placeholder.svg"
                                                 alt="{{$livre->formaterTitre()}} - Couverture non-disponible"
                                                 class="carte__image">
                                        @endif
                                    </div>

                                    {{------   Titre  ------}}
                                    <h3 class="carte__titre">{{$livre->formaterTitre()}} </h3>

                                    {{------   Auteurs  ------}}
                                    <div class="carte__auteur">
                                        @foreach ($livre->getAuteurs() as $auteur)
                                            @if ($loop->last)
                                                <span>{{$auteur->getNomPrenom()}}</span>
                                            @else
                                                <span>{{$auteur->getNomPrenom()}},&nbsp;</span>
                                            @endif
                                        @endforeach
                                    </div>

                                    <div class="carte__footer">
                                        {{------   Évaluation ------}}
                                        <p class="carte__footerEvaluation">4/5<span
                                                    class="icone etoile carte__footer--icone"></span>
                                        </p>

                                        {{------   Prix  ------}}
                                        <p class="carte__footerPrix">{{$livre->formaterPrix()}}$</p>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                </section>
                @include('fragments.pagination')
            </div>
        </div>
@endsection
