@extends('gabarit')

@section('contenu')
    <div class="fiche">
        <div class="livre conteneur">
            <div class="filAriane">
                <div>
                    @include('fragments.filariane')
                </div>
            </div>
            <div class="etat--mobile">
                    <span>
                        {{$livre->getParution()->etat}}
                    </span>
            </div>
            <div class="livre__image">
                @if(!isset($_SESSION['preferences']['categorie']))
                    <a id="btnPanierIcone"
                       href="index.php?controleur=panier&action=ajouterItem&isbn={{$livre->isbn}}&categorie={{$_GET['categorie']}}"
                       class="livre__imageLien bouton--plein">
                        @else
                            <a id="btnPanierIcone"
                               href="index.php?controleur=panier&action=ajouterItem&isbn={{$livre->isbn}}"
                               class="livre__imageLien bouton--plein">
                                @endif
                                <span class="screen-reader-only">Ajouter au panier</span>
                                <svg class="livre__imageLienSVG icone">
                                    <use xlink:href="#icone_ajout_panier"/>
                                </svg>
                            </a>
                    </a>
                    @if( $livre->formaterIsbn() !== "placeholder")
                        <picture class="livre__imagePicture">
                            <source media="(max-width:600px)"
                                    srcset="liaisons/images/couvertures_livres/{{$livre->formaterIsbn()}}__320.jpg">
                            <source media="(min-width:601px)"
                                    srcset="liaisons/images/couvertures_livres/{{$livre->formaterIsbn()}}.jpg">
                            <img src="liaisons/images/couvertures_livres/{{$livre->formaterIsbn()}}.jpg"
                                 alt="{{$livre->formaterTitre()}}"
                                 class="livre__imageImg">
                        </picture>
                    @else
                        <img src="liaisons/images/autres/couverture_placeholder.svg"
                             alt="{{$livre->formaterTitre()}} - Couverture non-disponible"
                             class="livre__imageImg">
                    @endif
            </div>
            <div class="livre__info">
                <div class="etat--bureau">
                    <span>
                        {{$livre->getParution()->etat}}
                    </span>
                </div>
                <div class="titre">
                    <h1 class="livre__infoTitre h2">{{$livre->formaterTitre()}}</h1>
                    @if( $livre->sous_titre !== null )
                        <h2 class="livre__infoSousTitre h3">{{$livre->sous_titre}}</h2>
                    @endif
                    <div class="auteur">
                        <span>Par </span>
                        @foreach($livre->getAuteurs() as $auteur)
                            @if($auteur->url_blogue)
                                <a href="{{$auteur->url_blogue}}" class="lienAnime">
                            @endif
                             @if($loop->last)
                                  <p class="auteurNom">{{$auteur->getNomPrenom()}}</p>
                                   @if($auteur->url_blogue)
                                        <svg class="lienExterne icone">
                                            <use xlink:href="#icone_lien_externe"/>
                                        </svg>
                                         </a>
                                    @endif
                            @else
                                <p class="auteurNom">{{$auteur->getNomPrenom()}}</p>
                                @if($auteur->url_blogue)
                                    <svg class="lienExterne icone">
                                        <use xlink:href="#icone_lien_externe"/>
                                    </svg>
                                    </a>
                                @endif<span>,</span>
                            @endif
                        @endforeach
                    </div>
                </div>
                @if(!isset($_SESSION['preferences']['categorie']))
                    <form action="index.php?controleur=panier&action=ajouterItem&isbn={{$livre->isbn}}&categorie={{$_GET['categorie']}}"
                          method="post">
                        @else
                            <form action="index.php?controleur=panier&action=ajouterItem&isbn={{$livre->isbn}}"
                                  method="post">
                                @endif
                                <div class="panier">
                                    <ul class="panier__format">
                                        <li class="panier__formatItem" role="radio">
                                            <input type="radio" id="papier" name="format" value="papier"
                                                   class="panier__formatItemRadio visuallyhidden"
                                                   checked aria-checked="true" required>
                                            <label class="panier__formatItemLabel" for="papier">Papier <span
                                                        class="prix">{{$livre->formaterPrix()}}$</span></label>
                                        </li>
                                        <li class="panier__formatItem" role="radio">
                                            <input type="radio" id="numerique" name="format" value="numerique"
                                                   class="panier__formatItemRadio visuallyhidden" required>
                                            <label class="panier__formatItemLabel" for="numerique">Numérique <span
                                                        class="prix">{{$livre->formaterPrix()}}$</span></label>
                                        </li>
                                    </ul>
                                    <div class="panier__div">
                                        <div class="panier__quantite">
                                            <label for="quantite" class="screen-reader-only">Quantite</label>
                                            <select name="quantite" id="quantite" class="panier__quantiteSelect">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                            </select>
                                        </div>
                                        <div class="panier__bouton">
                                            <button id="btnPanier" type="submit"
                                                    class="panier__boutonBtn btn bouton--vide">Ajouter au panier
                                                <svg class="livre__imageLienSVG icone">
                                                    <use xlink:href="#icone_ajout_panier_noir"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                    </form>
            </div>
        </div>
        <div class="gris">
            <div class="onglets conteneur">
                <input type="radio" name="onglets" id="onglet1" class="onglets__input" checked="checked">
                <label for="onglet1" class="onglets__label">Description</label>
                <div class="onglet description">
                    <h2 class="h2 description__titre">Description</h2>
                    <p class="contenu description__texte">
                        {!!$livre->description!!}
                    </p>
                </div>
                <input type="radio" name="onglets" id="onglet2" class="onglets__input">
                <label for="onglet2" class="onglets__label">Informations de publication</label>
                <div class="onglet">
                    <h2 class="h2 information">Informations de publication</h2>
                    <ul class="contenu information__liste">
                        <li class="information__listeItem">Nombre de pages <span
                                    class="bold">{{$livre->nbre_pages}}</span></li>
                        <li class="information__listeItem">Numéro international normalisé du livre (ISBN) <span
                                    class="bold">{{$livre->isbn}}</span></li>
                        <li class="information__listeItem">Année d’édition <span
                                    class="bold">{{$livre->annee_publication}}</span></li>
                        <li class="information__listeItem">Maison d'édition
                            @foreach($livre->getEditeur() as $editeur)
                                @if($editeur->url)
                                    <a href="{{$editeur->url}}" class="information__listeItemLien editeur">
                                        @endif
                                        @if($loop->last)
                                            <p class="bold lienAnime">{{$editeur->nom}}</p>
                                            @if($editeur->url)
                                                <svg class="lienExterne icone">
                                                    <use xlink:href="#icone_lien_externe"/>
                                                </svg>
                                                </a>
                                            @endif
                                        @else
                                            <p class="bold lienAnime">{{$editeur->nom}}</p>
                                            @if($editeur->url)
                                                <svg class="lienExterne icone">
                                                    <use xlink:href="#icone_lien_externe"/>
                                                </svg><span>,</span>
                                                </a>
                                            @endif
                                        @endif
                            @endforeach
                        </li>
                        @if( count($livre->getCollection()) !== 0 )
                            <li class="information__listeItem">Collection
                                @foreach($livre->getCollection() as $collection)
                                    <span class="bold">{{$collection->nom}}</span>
                                @endforeach
                            </li>
                        @endif
                        @if( $livre->autres_caracteristiques !== null)
                            <li class="information__listeItem">Caractéristiques <span
                                        class="bold">{!!$livre->autres_caracteristiques!!}</span></li>
                        @endif
                    </ul>
                </div>
                @if( count($livre->getHonneurs()) !== 0 || count($livre->getRecensions()) !== 0 )
                    <input type="radio" name="onglets" id="onglet3" class="onglets__input">
                    <label for="onglet3" class="onglets__label">Prix et mentions</label>
                    <div class="onglet prix">
                        @if(count($livre->getHonneurs()) !== 0)
                            <h2 class="h2 prix__titre">Prix</h2>
                            <p class="contenu prix__texte">
                                @foreach($livre->getHonneurs() as $honneur)
                                    <span>{{$honneur->nom}} </span>
                                @endforeach
                            </p>
                        @endif

                        @if(count($livre->getRecensions()) !== 0)
                            <h2 class="h2 prix__titre">Mentions</h2>
                            <div class="mentions">
                                @foreach($livre->getRecensions() as $mention)
                                    <article class="mention">
                                        <header class="mention__header">
                                            <time datetime="" class="mention__date">{{$mention->formaterDate()}}</time>
                                            <h3 class="mention__journal h3">{{$mention->nom_media}}</h3>
                                        </header>
                                        <p class="mention_texte">
                                            {!!$mention->description!!}
                                        </p>
                                        <footer class="mention__footer">
                                            <p class="mention__auteur">
                                                Par {{$mention->nom_journaliste}}
                                            </p>
                                            <p class="mention__titre">
                                                Pour <span class="italic">{{$mention->formaterTitre()}}</span>
                                            </p>
                                        </footer>
                                    </article>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="evaluation conteneur">
            <div class="evaluation__globale">
                <h2 class="evaluation__globaleTitre h2">Évaluation</h2>
                <h3 class="h4 evaluation__globaleSousTitre">Cote globale</h3>
                <p class="evaluation__globaleNote">
                <span class="texte_large">4,5
                    <span class="icone etoile"></span>
                </span>/5
                </p>
                <p class="evaluation__globaleNb">1 évaluation</p>
            </div>
            <div class="evaluation__commentaire">
                <button class="evaluation__commentaireBtn btn bouton--vide">Écrire une évaluation&nbsp;
                    <svg class="evaluation__commentaireBtnSVG icone">
                        <use xlink:href="#icone_evaluations"/>
                    </svg>
                </button>
                <div class="commentaire">
                    <div class="commentaire__utilisateur">
                        <p class="h2">Josie</p>
                        <span class="date">Il y a 5 heures</span>
                    </div>
                    <span class="commentaire__note">4,5/5
                        <span class="icone etoile"></span>
                    </span>
                    <div class="commentaire__texte">
                        <p class="commentaire__texteP">Quand j'ai acheté ce livre, je ne pensais pas l'aimer autant! Je
                            l'ai lu tout d'un coup et je le recommande fortement à tous! L'auteur manipule habilement
                            les mots pour recréer une ambience historique incroyable. En me fermant les yeux, je
                            pouvais, par la force des mots, imaginer l'odeur, les sons, les bruits des scènes décrites
                            dans ce roman.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if( isset($message) )
        <div class="boiteModale" id="boiteModale">
            <div id="boiteModale__background" class="boiteModale__dialogue">
                <div class="boiteModale__fenetre">
                    <header class="boiteModale__entete">
                        <p class="boiteModale__enteteTitre">
                            {{$livre->formaterTitre()}} - Ajouté au panier!
                        </p>
                        <span class="fermerModal screen-reader-only">Fermer</span>
                        <div id="fermer" class="boiteModale__enteteSupprimer">
                            <svg class="icone">
                                <use xlink:href="#icone_supprimer"/>
                            </svg>
                        </div>
                    </header>
                    <div class="boiteModale__contenu">
                        <div class="boiteModale__contenuBg">
                            <div class="boiteModale__contenuImage">
                                @if( $livre->formaterIsbn() !== "placeholder")
                                    <picture class="boiteModale__contenuImagePicture">
                                        <source media="(max-width:600px)"
                                                srcset="liaisons/images/couvertures_livres/{{$livre->formaterIsbn()}}__320.jpg">
                                        <source media="(min-width:601px)"
                                                srcset="liaisons/images/couvertures_livres/{{$livre->formaterIsbn()}}.jpg">
                                        <img src="liaisons/images/couvertures_livres/{{$livre->formaterIsbn()}}.jpg"
                                             alt="{{$livre->formaterTitre()}}"
                                             class="boiteModale__contenuImageImg">
                                    </picture>
                                @else
                                    <img src="liaisons/images/autres/couverture_placeholder.svg"
                                         alt="{{$livre->formaterTitre()}} - Couverture non-disponible"
                                         class="boiteModale__contenuImageImg">
                                @endif
                            </div>
                            <div class="boiteModale__contenuDiv">
                                <div class="boiteModale__contenuFlex">
                                    <div class="boiteModale__contenuTitre">
                                        {{$livre->formaterTitre()}}
                                    </div>
                                    <div class="boiteModale__contenuAuteur">
                                        @foreach($livre->getAuteurs() as $auteur)
                                            @if($auteur->url_blogue)
                                                <a href="{{$auteur->url_blogue}}" class="lienAnime">
                                            @endif
                                            @if($loop->last)
                                                 <p class="boiteModale__contenuAuteurNom">{{$auteur->getNomPrenom()}}</p>
                                                 @if($auteur->url_blogue)
                                                      <svg class="lienExterne icone">
                                                           <use xlink:href="#icone_lien_externe"/>
                                                      </svg>
                                                </a>
                                                @endif
                                            @else
                                                <p class="boiteModale__contenuAuteurNom">{{$auteur->getNomPrenom()}}</p>                                                                @if($auteur->url_blogue)
                                                    <svg class="lienExterne icone">
                                                        <use xlink:href="#icone_lien_externe"/>
                                                    </svg>
                                                    </a>
                                                @endif <span>,</span>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="boiteModale__contenuFlex auteurPrix">
                                        <div class="boiteModale__contenuQuantite">
                                            <span>Quantité : {{$message}}</span>
                                        </div>
                                        <div class="boiteModale__contenuPrix">
                                            <span>{{$livre->formaterPrix()}} $</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <footer class="boiteModale__footer">
                        <a href="index.php?controleur=panier&action=fiche" class="boiteModale__footerPanier bouton--plein">Visualiser
                            le panier
                            <svg class="icone">
                                <use xlink:href="#icone_fleche_droite"/>
                            </svg>
                        </a>
                        <a href="index.php?controleur=livre&action=index" class="lienAnime">Continuer à magasiner</a>
                    </footer>
                </div>
            </div>
        </div>

        {{--TEMPORAIRE--}}
        <script>
            var btn = document.getElementById('fermer');
            var modale = document.getElementById('boiteModale');
            var background = document.getElementById('boiteModale__background');

            btn.onclick = function () {
                modale.style.display = "none";
            };

            // When the user clicks anywhere outside of the modal, close it
            background.onclick = function () {
                modale.style.display = "none";
            };
        </script>
    @endif
@endsection