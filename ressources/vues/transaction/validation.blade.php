@extends('gabarit')

@section('contenu')
    <div class="validation conteneur transaction">

        <h1>Validation</h1>

        <div class="bouton__livraison">
            <a href="index.php?controleur=validation&action=inserer" class="bouton--vide">
                Passer la commande
                <svg class="icone">
                    <use xlink:href="#icone_livraison_noir"/>
                </svg>
            </a>
        </div>

        <div class="livraison__nomClient">
            <p>Livraison à : <strong>{{$arrLivraison['prenom']['valeur']}} {{$arrLivraison['nom']['valeur']}}</strong></p>
            <p>Date de livraison estimée : <span><strong>{{$arrModeLivraison->delai}}</strong></span></p>
        </div>

        <!--------------  Sommaire de la commande  ------------->

    <div class="confirmation">
        <div>
            <h2>Sommaire de la commande</h2>

            <div class="confirmation panier__total">
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
        </div>

    <div class="confirmation__sommaireCommande">
        <!--------------  Adresse de livraison  ------------->
        <h2>Adresse de livraison</h2>
        <div>
            <div class="confirmation__sommaireCommande__adresseLivraison gris_pale">
                <span><strong>{{$arrLivraison['prenom']['valeur']}} {{$arrLivraison['nom']['valeur']}}</strong></span>
                <p>{{$arrLivraison['adresse']['valeur']}}</p>
                <p>{{$arrLivraison['ville']['valeur']}}</p>
                <p>{{$provinceLivraison}}</p>
                <p>{{$arrLivraison['codePostal']['valeur']}}</p>
                <div class="validation__modifier"><a href="#" class="bouton--vide">Modifier</a></div>
            </div>
        </div>


        <!--------------------------   Informations de facturation  --------------------------------------->

        <h2>Informations de facturation</h2>
        <div>
            <!--------------  Mode de paiement  ---------------->
            <div class="confirmation__sommaireCommande__modePaiment gris">
                <h3>Mode de paiement :
                    @if(isset($arrFacturation) && ($arrFacturation['modePaiement']['valeur'] == 'carteCredit'))
                        Carte de crédit
                    @else
                        PayPal
                    @endif
                </h3>

                @if(isset($arrFacturation) && ($arrFacturation['modePaiement']['valeur'] == 'carteCredit'))
                    @switch($arrFacturation['cartesCredit']['valeur'])
                        @case('visa')
                        <svg class="icone">
                            <use xlink:href="#icone_visa"/>
                        </svg>
                        @break
                        @case('masterCard')
                        <svg class="icone">
                            <use xlink:href="#icone_masterCard"/>
                        </svg>
                        @break
                        @case('amex')
                        <svg class="icone">
                            <use xlink:href="#icone_amex"/>
                        </svg>
                        @break
                    @endswitch

                    <span>{{$carteCache}}</span>

                    <p>Expiration {{$arrFacturation['moisCarte']['valeur']}}
                        / {{$arrFacturation['anneeCarte']['valeur']}}</p>
                        <div class="validation__modifier"><a type="button" class="bouton--vide">Modifier</a></div>
                @endif
            </div>

            <!--------------  Adresse de facturation  ------------->

            <div class="confirmation__sommaireCommande__adresseFacturation gris_pale">
                <h3>Adresse de facturation</h3>
                <span><strong>{{$arrFacturation['prenom']['valeur']}} {{$arrFacturation['nom']['valeur']}}</strong></span>
                <p>{{$arrFacturation['adresse']['valeur']}}</p>
                <p>{{$arrFacturation['ville']['valeur']}}</p>
                <p>{{$arrFacturation['province']['valeur']}}</p>
                <p>{{$arrFacturation['codePostal']['valeur']}}</p>
                <div class="validation__modifier"><a href="#" class="bouton--vide">Modifier</a></div>
            </div>

            <!--------------  Informations du client  ------------->
            <div class="confirmation__sommaireCommande__informations gris">
                <h3>Informations</h3>
                <p>
                    <svg class="icone">
                        <use xlink:href="#icone_courriel"/>
                    </svg>
                    {{$arrClient->courriel}}
                </p>

                <p>
                    <svg class="icone">
                        <use xlink:href="#icone_telephone"/>
                    </svg>
                    {{$numeroTelFormate}}
                </p>
                <div class="validation__modifier"><a href="#" class="bouton--vide">Modifier</a></div>
            </div>
        </div>

        <!------------------------------   Livres au panier  --------------------------------------->

        <div class="panier__items section--espace">
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
                                {{$livre->livre->titre}}
                            </p>
                            <p class="panier__itemAuteur">
                                @foreach($livre->livre->getAuteurs() as $auteur)
                                    <span class="panier__itemAuteurNom">{{$auteur->getNomPrenom()}}</span>
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
                                <button type="submit" class="bouton__js">Mettre à jour la quantité</button>
                            </form>
                        </div>
                        <div class="panier__itemPrix">
                            <span class="panier__itemPrixTexte"> {{$livre->formaterPrix($livre->getMontantTotal())}}
                                $</span>
                        </div>
                        <div class="panier__itemSupprimer">
                            <form action="index.php?controleur=validation&action=supprimerItem&isbn={{$livre->livre->isbn}}"
                                  method="post">
                                <button type="submit">
                                    <span class="screen-reader-only">Retirer du panier</span>
                                    <svg class="icone">
                                        <use xlink:href="#icone_fermer"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

        <!------------------------------   Montants panier  --------------------------------------->

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
                    <p class="panier__sousTotalPrix prix">{{$infosPanier->formaterPrix($infosPanier->getMontantSousTotal())}}
                        $</p>
                </div>
                <div class="panier__tps panier__totalPrixSection">
                    <p class="panier__tpsTitre titre"><span class="bold">TPS</span> (5%)</p>
                    <p class="panier__tpsPrix prix">{{$infosPanier->formaterPrix($infosPanier->getMontantTPS())}}$</p>
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
                                    @if( (int) $infosPanier->formaterPrix($infosPanier->getMontantSousTotal()) >= 50)
                                        <option value="gratuit" selected="selected">
                                            Gratuit
                                        </option>
                                        <option value="standard"
                                                @if($arrModeLivraison->mode_livraison=='standard') selected="selected" @endif >
                                            Standard
                                        </option>
                                        <option value="prioritaire"
                                                @if($arrModeLivraison->mode_livraison=='prioritaire') selected="selected" @endif >
                                            Prioritaire
                                        </option>
                                    @else
                                        <option value="standard"
                                                @if($arrModeLivraison->mode_livraison=='standard') selected="selected" @endif >
                                            Standard
                                        </option>
                                        <option value="prioritaire"
                                                @if($arrModeLivraison->mode_livraison=='prioritaire') selected="selected" @endif >
                                            Prioritaire
                                        </option>
                                    @endif
                                </select>
                            </div>
                            <button type="submit" class="panier__LivraisonBouton bouton__js">Mettre à jour le type de
                                livraison
                            </button>
                        </form>
                        <div class="panier__LivraisonDate">
                            <p>Date estimée de livraison : <span
                                        class="date">{{$arrModeLivraison->delai}}</span></p>
                        </div>
                    </div>
                    <div class="panier__LivraisonPrix prix">
                        <span>{{$infosPanier->getMontantFraisLivraison()}}$</span>
                    </div>
                </div>
                <div class="panier__prix panier__totalPrixSection">
                    <p class="panier__prixTitre titre">Total</p>
                    <p class="panier__prixPrix prix">{{$infosPanier->formaterPrix($infosPanier->getMontantTotal())}}
                        $</p>
                </div>
            </div>
            <div class="bouton__livraison">
                <a href="index.php?controleur=validation&action=inserer" class="bouton--vide">
                    Passer la commande
                    <svg class="icone">
                        <use xlink:href="#icone_livraison_noir"/>
                    </svg>
                </a>
            </div>
        </div>
@endsection