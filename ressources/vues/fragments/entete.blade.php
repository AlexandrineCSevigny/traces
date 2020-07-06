@if( !isset($_GET['controleur']) )
    <nav class="conteneur menu" role="navigation" aria-label="Navigation principale">
        <div class="menu__logo">
            <a href="index.php?controleur=site&action=accueil"><span class="screen-reader-only">Accueil</span>
                <svg class="menu__logoSVG logo icone">
                    <use xlink:href="#logo_traces"/>
                </svg>
            </a>
            <div class="logo_panier_mobile">
                <a href="index.php?controleur=panier&action=fiche" class="menu__lien">
                    <span>(<span class="nbItem">{{$panier}}</span>)</span>
                    <svg class="icone">
                        <use xlink:href="#icone_mon_panier"/>
                    </svg>
                </a>
            </div>
        </div>
        <div class="recherche" role="search">
            <form action="index.php?controleur=site&action=afficherResultats&js=inactif" method="post"
                  class="recherche__form">
                <div class="recherche__sujet">
                    <label for="sujet">
                    <span class="screen-reader-only">
                        Filtrer la recherche
                    </span>
                    </label>
                    <select name="sujet" id="sujet" class="recherche__sujetFiltre">
                        <option value="auteur"
                                @if(isset($_POST['sujet']) && $_POST['sujet'] == 'auteur') selected @endif>Auteur
                        </option>
                        <option value="isbn" @if(isset($_POST['sujet']) && $_POST['sujet'] == 'isbn')  selected @endif>
                            ISBN
                        </option>
                        <option value="sujet" @if(isset($_POST['sujet']) && $_POST['sujet'] == 'sujet') selected @endif>
                            Sujet
                        </option>
                        <option value="titre" @if(isset($_POST['sujet']) && $_POST['sujet'] == 'titre')selected @endif>
                            Titre
                        </option>
                    </select>
                </div>
                <div class="recherche__rechercher">
                    <label for="recherche" class="screen-reader-only">Rechercher</label>
                    <input type="search" id="recherche" class="recherche__rechercherInput" name="recherche"
                           autocomplete="off" placeholder="Écrire un mot-clé"
                           @if(isset($_POST['recherche']))value="{{$_POST['recherche']}}" @endif>
                    <ul class="recherche__aideSaisie"></ul>
                </div>
                <button type="submit" class="recherche__button">
                    <span class="screen-reader-only">Rechercher</span>
                    <svg class="icone recherche__buttonSVG">
                        <use xlink:href="#icone_recherche"/>
                    </svg>
                </button>
            </form>
        </div>

        <div class="conteneurListe">
            <ul class="menu__liste principale">
                <li class="menu__listeItem">
                    <a href="index.php?controleur=livre&action=index"
                       class="menu__lien lienAnime @if($_GET['controleur']== 'livre' && $_GET['action'] == 'index') lienActif @endif">Catalogue</a>
                </li>
                <li class="menu__listeItem">
                    <a href="#" class="menu__lien lienAnime">Meilleurs vendeurs</a>
                </li>
                <li class="menu__listeItem">
                    <a href="index.php?controleur=site&action=apropos"
                       class="menu__lien lienAnime @if($_GET['controleur']== 'site' && $_GET['action'] == 'apropos') lienActif @endif">Découvrir
                        Traces</a></li>
                <li class="menu__listeItem"><a href="#" class="menu__lien lienAnime">Auteurs</a></li>
                <li class="menu__listeItem"><a href="#" class="menu__lien lienAnime">Nous joindre</a></li>
            </ul>
            <ul class="menu__liste secondaire">
                <li class="menu__listeItem">
                    @if(ISSET($_SESSION['connecte']))
                        <a href="index.php?controleur=client&action=deconnecter" class="menu__lien lienAnime">
                            Se déconnecter
                        </a>
                    @else
                        <a href="index.php?controleur=client&action=afficher" class="menu__lien lienAnime">
                            Se connecter
                        </a>
                    @endif
                </li>
                <li class="menu__listeItem">
                    @if(ISSET($_SESSION['connecte']))
                        <a href="#" class="menu__lien lienAnime">Bonjour, <b>{{{$_SESSION['client']->prenom}}}</b>
                            <svg class="icone">
                                <use xlink:href="#icone_mon_compte"/>
                            </svg>
                        </a>
                    @else
                        <a href="#" class="menu__lien lienAnime">Mon compte
                            <svg class="icone">
                                <use xlink:href="#icone_mon_compte"/>
                            </svg>
                        </a>
                    @endif
                </li>
                <li class="menu__listeItem">
                    <a href="#" class="menu__lien lienAnime">English</a>
                </li>

                {{--On ajoute une classe pour pouvoir le retirer du menu mobile, mais le garder en bureau--}}
                <li class="menu__listeItem retirerMenuMobile">
                    <a href="index.php?controleur=panier&action=fiche"
                       class="menu__lien lienAnime @if($_GET['controleur']== 'panier' && $_GET['action'] == 'fiche') lienActif @endif">Mon
                        panier (<span class="nbItem">{{$panier}}</span>)
                        <svg class="icone">
                            <use xlink:href="#icone_mon_panier"/>
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
@else
    @switch( $_GET['controleur'] )
        @case('livraison')
        <nav class="conteneur menu" role="navigation" aria-label="Navigation principale">
            <div class="menu__logo">
                <svg class="menu__logoSVG logo icone">
                    <use xlink:href="#logo_traces"/>
                </svg>
            </div>
            <div class="recherche">
                <p class="etapes h3">
                    <span class="etape etape--actif">Livraison</span> >
                    <span class="etape">Facturation</span> >
                    <span class="etape">Validation</span>
                </p>
            </div>
        </nav>
        @break
        @case('facturation')
        <nav class="conteneur menu" role="navigation" aria-label="Navigation principale">
            <div class="menu__logo">
                <svg class="menu__logoSVG logo icone">
                    <use xlink:href="#logo_traces"/>
                </svg>
            </div>
            <div class="recherche">
                <p class="etapes h3">
                    <span class="etape">Livraison</span> >
                    <span class="etape etape--actif">Facturation</span> >
                    <span class="etape">Validation</span>
                </p>
            </div>
        </nav>
        @break
        @case('validation')
        <nav class="conteneur menu" role="navigation" aria-label="Navigation principale">
            <div class="menu__logo">
                <svg class="menu__logoSVG logo icone">
                    <use xlink:href="#logo_traces"/>
                </svg>
            </div>
            <div class="recherche">
                <p class="etapes h3">
                    <span class="etape">Livraison</span> >
                    <span class="etape">Facturation</span> >
                    <span class="etape etape--actif">Validation</span>
                </p>
            </div>
        </nav>
        @break
        @default
        <nav class="conteneur menu" role="navigation" aria-label="Navigation principale">
            <div class="menu__logo">
                <a href="index.php?controleur=site&action=accueil"><span class="screen-reader-only">Accueil</span>
                    <svg class="menu__logoSVG logo icone">
                        <use xlink:href="#logo_traces"/>
                    </svg>
                </a>
                <div class="logo_panier_mobile">
                    <a href="index.php?controleur=panier&action=fiche" class="menu__lien">
                        <span>(<span class="nbItem">{{$panier}}</span>)</span>
                        <svg class="icone">
                            <use xlink:href="#icone_mon_panier"/>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="recherche" role="search">
                <form action="index.php?controleur=site&action=afficherResultats&js=inactif" method="post"
                      class="recherche__form">
                    <div class="recherche__sujet">
                        <label for="sujet">
                    <span class="screen-reader-only">
                        Filtrer la recherche
                    </span>
                        </label>
                        <select name="sujet" id="sujet" class="recherche__sujetFiltre">
                            <option value="auteur"
                                    @if(isset($_POST['sujet']) && $_POST['sujet'] == 'auteur') selected @endif>Auteur
                            </option>
                            <option value="isbn"
                                    @if(isset($_POST['sujet']) && $_POST['sujet'] == 'isbn')  selected @endif>
                                ISBN
                            </option>
                            <option value="sujet"
                                    @if(isset($_POST['sujet']) && $_POST['sujet'] == 'sujet') selected @endif>
                                Sujet
                            </option>
                            <option value="titre"
                                    @if(isset($_POST['sujet']) && $_POST['sujet'] == 'titre')selected @endif>
                                Titre
                            </option>
                        </select>
                    </div>
                    <div class="recherche__rechercher">
                        <label for="recherche" class="screen-reader-only">Rechercher</label>
                        <input type="search" id="recherche" class="recherche__rechercherInput" name="recherche"
                               autocomplete="off"
                               @if(isset($_POST['recherche']))value="{{$_POST['recherche']}}" @endif>
                        <ul class="recherche__aideSaisie"></ul>
                    </div>
                    <button type="submit" class="recherche__button">
                        <span class="screen-reader-only">Rechercher</span>
                        <svg class="icone recherche__buttonSVG">
                            <use xlink:href="#icone_recherche"/>
                        </svg>
                    </button>
                </form>
            </div>

            <div class="conteneurListe">
                <ul class="menu__liste principale">
                    <li class="menu__listeItem">
                        <a href="index.php?controleur=livre&action=index"
                           class="menu__lien lienAnime @if($_GET['controleur']== 'livre' && $_GET['action'] == 'index') lienActif @endif">Catalogue</a>
                    </li>
                    <li class="menu__listeItem">
                        <a href="#" class="menu__lien lienAnime">Meilleurs vendeurs</a>
                    </li>
                    <li class="menu__listeItem">
                        <a href="index.php?controleur=site&action=apropos"
                           class="menu__lien lienAnime @if($_GET['controleur']== 'site' && $_GET['action'] == 'apropos') lienActif @endif">Découvrir
                            Traces</a></li>
                    <li class="menu__listeItem"><a href="#" class="menu__lien lienAnime">Auteurs</a></li>
                    <li class="menu__listeItem"><a href="#" class="menu__lien lienAnime">Nous joindre</a></li>
                </ul>
                <ul class="menu__liste secondaire">
                    <li class="menu__listeItem">
                        @if(isset($_SESSION['connecte']))
                            <a href="index.php?controleur=client&action=deconnecter" class="menu__lien lienAnime">
                                Se déconnecter
                            </a>
                        @else
                            <a href="index.php?controleur=client&action=afficher" class="menu__lien lienAnime">
                                Se connecter
                            </a>
                        @endif
                    </li>
                    <li class="menu__listeItem">
                        @if(isset($_SESSION['connecte']))
                            <a href="#" class="menu__lien lienAnime">Bonjour, <b>{{$_SESSION['client']->prenom}}</b>
                                <svg class="icone">
                                    <use xlink:href="#icone_mon_compte"/>
                                </svg>
                            </a>
                        @else
                            <a href="#" class="menu__lien lienAnime">Mon compte
                                <svg class="icone">
                                    <use xlink:href="#icone_mon_compte"/>
                                </svg>
                            </a>
                        @endif
                    </li>
                    <li class="menu__listeItem">
                        <a href="#" class="menu__lien lienAnime">English</a>
                    </li>

                    {{--On ajoute une classe pour pouvoir le retirer du menu mobile, mais le garder en bureau--}}
                    <li class="menu__listeItem retirerMenuMobile">
                        <a href="index.php?controleur=panier&action=fiche"
                           class="menu__lien lienAnime @if($_GET['controleur']== 'panier' && $_GET['action'] == 'fiche') lienActif @endif">Mon
                            panier (<span class="nbItem">{{$panier}}</span>)
                            <svg class="icone">
                                <use xlink:href="#icone_mon_panier"/>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    @endswitch
@endif