@extends('gabarit')

@section('contenu')

    <div class="conteneur confirmation">
        <h1>Confirmation d'achat</h1>

        <div class="confirmation__imageConfirmation">
            <div class="confirmation__imageConfirmation__conteneur">
                <img alt="Merci de votre achat !" class="confirmation__imageConfirmation__image"
                     src="./../public/liaisons/images/autres/illustration_achat_confirme.svg">
            </div>
        </div>

        <div class="confirmation__texteConfirmation">
            <p>Votre commande vous sera expédiée selon les modalités que vous avez choisies. N'hésitez pas à consulter
                notre service à la clientèle pour plus d'informations relatives à votre commande ou votre compte.</p>

            <p>Votre numéro de confirmation est le&nbsp;: <strong>7463-4846-2245</strong></p>
        </div>

        <div class="confirmation__sommaireCommande">
            <h2>Sommaire de votre commande</h2>

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

            <div class="confirmation__sommaireCommande__adresseLivraison gris_pale">
                <h3>Adresse de livraison</h3>
                <span>{{$arrLivraison['prenom']['valeur']}}</span>
                <span>{{$arrLivraison['nom']['valeur']}}</span>
                <p>{{$arrLivraison['adresse']['valeur']}}</p>
                <p>{{$arrLivraison['ville']['valeur']}}</p>
                <p>{{$provinceLivraison}}</p>
                <p>{{$arrLivraison['codePostal']['valeur']}}</p>
            </div>

            <div class="confirmation__sommaireCommande__adresseFacturation gris">
                <h3>Adresse de facturation</h3>
                <span>{{$arrFacturation['prenom']['valeur']}}</span>
                <span>{{$arrFacturation['nom']['valeur']}}</span>
                <p>{{$arrFacturation['adresse']['valeur']}}</p>
                <p>{{$arrFacturation['ville']['valeur']}}</p>
                <p>{{$arrFacturation['province']['valeur']}}</p>
                <p>{{$arrFacturation['codePostal']['valeur']}}</p>
            </div>

            <div class="confirmation__sommaireCommande__modePaiment gris_pale">
                <h3>Mode de paiement :
                    @if($arrFacturation['modePaiement']['valeur'] == 'carteCredit')
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

                @endif
            </div>

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
            </div>
        </div>

        <div class="confirmation__commande">
            <h2>Commande</h2>
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
                                <p>Quantité : {{$livre->quantite}}</p>
                            </div>
                            <div class="panier__itemPrix">
                            <span class="panier__itemPrixTexte">Prix : {{$livre->formaterPrix($livre->getMontantTotal())}}
                                $</span>
                            </div>
                            <div class="panier__itemPrixTotal">Total
                                : {{$livre->formaterPrix($livre->getMontantTotal())}}$
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        <div class="confirmation__boutonImprimer">
            <a class="bouton--vide" href="#" onclick="window.print();">Imprimer le reçu de votre commande</a>
        </div>

        <div class="confirmation__lienContinuerMagasinage">
            <a class="lienAnime" href="index.php?controleur=livre&action=index">Continuer à magasiner
                <svg class="icone">
                    <use xlink:href="#icone_livraison_noir"/>
                </svg>
            </a>
        </div>
    </div>
@endsection