@extends('gabarit')

@section('contenu')
    <div class="panier conteneur">
        <div class="panier__titre">
            <h1 class="h1">Panier</h1>
            @if( !empty($livres))
                <span class="nbArticles">{{$panier}} @if($panier > 1) articles @else article @endif</span>
            @endif
        </div>
        @if( empty($livres))
            <div>
                <div class="page--vide">
                    <img alt="Erreur 404" class="erreur404__image"
                         src="./../public/liaisons/images/autres/illustration_panier_vide.svg">
                    <a href="index.php?controleur=livre&action=index" class="bouton bouton--redirection">Continuer à
                        magasiner</a>
                </div>
            </div>
        @else
            <div class="panier__header">
                <span class="panier__headerTotal">Sous-total : <span class="bold">{{$sousTotal}}$</span></span>
                @if( $sousTotal >= 50 )
                    <p class="panier__headerMessage">
                        <svg class="icone">
                            <use xlink:href="#icone_livraison_blanc"/>
                        </svg>
                        <span class="panier__headerMessageTexte">Admissible à la livraison gratuite&nbsp;! </span>
                    </p>
                @endif
            </div>
            <div class="panier__sousTitre">
                <span class="panier__sousTitreProduit">Produit</span>
                <span class="panier__sousTitreQuantite">Quantité</span>
                <span class="panier__sousTitrePrix">Prix</span>
            </div>
            <div class="panier__items">
                @foreach($livres as $livre)
                    <div class="panier__item">
                        @if( $livre->livre->formaterIsbn() !== "placeholder")
                            <picture class="panier__itemPicture">
                                <source media="(max-width:600px)"
                                        srcset="liaisons/images/couvertures_livres/{{$livre->livre->formaterIsbn()}}__320.jpg">
                                <source media="(min-width:601px)"
                                        srcset="liaisons/images/couvertures_livres/{{$livre->livre->formaterIsbn()}}.jpg">
                                <img src="liaisons/images/couvertures_livres/{{$livre->livre->formaterIsbn()}}.jpg"
                                     alt="{{$livre->livre->formaterTitre()}}"
                                     class="panier__itemImg"/>
                            </picture>
                        @else
                            <img src="liaisons/images/autres/couverture_placeholder.svg"
                                 alt="{{$livre->livre->formaterTitre()}} - Couverture non-disponible"
                                 class="livre__imageImg placeholder">
                        @endif
                        <div class="panier__flex">
                            <div>
                                <p class="panier__itemTitre">
                                    {{$livre->livre->formaterTitre()}}
                                </p>
                                <p class="panier__itemAuteur">
                                    @foreach($livre->livre->getAuteurs() as $auteur)
                                        @if($loop->last)
                                            <span class="panier__itemAuteurNom">{{$auteur->getNomPrenom()}}</span>
                                        @else
                                            <span class="panier__itemAuteurNom">{{$auteur->getNomPrenom()}},</span>
                                        @endif
                                    @endforeach
                                </p>
                            </div>
                            <div class="panier__itemQuantite">
                                <form action="index.php?controleur=panier&action=majQuantite&isbn={{$livre->livre->isbn}}&pasJs=true"
                                      method="post" class="formulaire">
                                    <div class="quantite select__conteneur">
                                        <label for="{{$livre->livre->isbn}}" class="screen-reader-only">Quantite</label>
                                        <select name="quantite" id="{{$livre->livre->isbn}}" class="quantiteSelect">
                                            <option value="1" @if($livre->quantite == 1)selected @endif >1</option>
                                            <option value="2" @if($livre->quantite == 2)selected @endif >2</option>
                                            <option value="3" @if($livre->quantite == 3)selected @endif >3</option>
                                            <option value="4" @if($livre->quantite == 4)selected @endif >4</option>
                                            <option value="5" @if($livre->quantite == 5)selected @endif >5</option>
                                            <option value="6" @if($livre->quantite == 6)selected @endif >6</option>
                                            <option value="7" @if($livre->quantite == 7)selected @endif >7</option>
                                            <option value="8" @if($livre->quantite == 8)selected @endif >8</option>
                                            <option value="9" @if($livre->quantite == 9)selected @endif >9</option>
                                            <option value="10" @if($livre->quantite == 10)selected @endif >10</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="bouton__js bouton--vide">Mettre à jour la quantité
                                    </button>
                                </form>
                            </div>
                            <div class="panier__itemPrix">
                                <span class="panier__itemPrixTexte"> {{$livre->formaterPrix($livre->getMontantTotal())}}$</span>
                            </div>
                            <div class="panier__itemSupprimer">
                                <form action="index.php?controleur=panier&action=supprimerItem&isbn={{$livre->livre->isbn}}"
                                      method="post">
                                    <button type="submit">
                                        <span class="screen-reader-only">Retirer du panier</span>
                                        <svg class="icone">
                                            <use xlink:href="#icone_supprimer_rose"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="panier__total">
                <div class="panier__totalInfo">
                    <span class="panier__totalInfoTexte">Tous les prix sont en argent canadien(CAD).</span>
                    <a href="#" class="panier__totalInfoLien lienAnime">Ce n'est pas votre devise? Cliquez ici.</a>
                </div>
                <div class="panier__totalPrix">
                    <div class="panier__sousTotal panier__totalPrixSection">
                        <div class="div">
                            <p class="panier__sousTotalTitre titre">Sous-total</p>
                            <p class="panier__sousTotalNbArticle">{{$panier}} @if($panier > 1) articles @else
                                    article @endif</p>
                        </div>
                        <p class="panier__sousTotalPrix prix">{{$sousTotal}}$</p>
                    </div>
                    <div class="panier__tps panier__totalPrixSection">
                        <p class="panier__tpsTitre titre"><span class="bold">TPS</span> (5%)</p>
                        <p class="panier__tpsPrix prix">{{$tps}}$</p>
                    </div>
                    <div class="panier__Livraison panier__totalPrixSection">
                        <div class="div">
                            <p class="panier__LivraisonTitre titre">Livraison</p>
                            <form class="panier__LivraisonForm formulaire"
                                  action="index.php?controleur=panier&action=majLivraison"
                                  method="post">
                                <div class="livraison select__conteneur">
                                    <label for="livraison" class="screen-reader-only">Livraison</label>
                                    <select name="livraison" id="livraison">
                                        @if( (int) $sousTotal >= 50)
                                            <option value="gratuit">
                                                Gratuit
                                            </option>
                                            <option value="standard"
                                                    @if($_SESSION['livraison']->mode_livraison=='standard') selected="selected" @endif >
                                                Standard
                                            </option>
                                            <option value="prioritaire"
                                                    @if($_SESSION['livraison']->mode_livraison=='prioritaire') selected="selected" @endif>
                                                Prioritaire
                                            </option>
                                        @else
                                            <option value="standard"
                                                    @if($_SESSION['livraison']->mode_livraison=='standard') selected="selected" @endif >
                                                Standard
                                            </option>
                                            <option value="prioritaire"
                                                    @if($_SESSION['livraison']->mode_livraison=='prioritaire') selected="selected" @endif >
                                                Prioritaire
                                            </option>
                                        @endif
                                    </select>
                                </div>
                                <button type="submit" class="panier__LivraisonBouton bouton__js bouton--vide">Mettre à
                                    jour le type de
                                    livraison
                                </button>
                            </form>
                            <div class="panier__LivraisonDate">
                                <p>Date estimée de livraison : <span
                                            class="date">{{$delai}}</span></p>
                            </div>
                        </div>
                        <div class="panier__LivraisonPrix prix">
                            <span>{{$livraison}}$</span>
                        </div>
                    </div>
                    <div class="panier__prix panier__totalPrixSection">
                        <p class="panier__prixTitre titre">Total</p>
                        <p class="panier__prixPrix prix">{{$total}}$</p>
                    </div>
                </div>
            </div>
            <div class="panier__liens">
                <a href="index.php?controleur=livre&action=index" class="panier__liensMagasiner lienAnime">Continuer à
                    magasiner</a>
                <a href="index.php?controleur=livraison&action=afficher" class="panier__liensCommande bouton--plein">
                    Passer la commande
                    <svg class="icone">
                        <use xlink:href="#icone_livraison_noir"/>
                    </svg>
                </a>
            </div>
        @endif
    </div>
@endsection