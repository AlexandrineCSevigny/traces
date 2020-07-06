@extends('courriels.gabarit')

@section('contenu')
    <div class="conteneur confirmation">

        <h1>Confirmation d'achat</h1>

        <img src="https://scontent.fyhu1-1.fna.fbcdn.net/v/t1.0-9/75380470_2548608168509065_5653253164376260608_n.jpg?_nc_cat=109&_nc_oc=AQmBa1Q3cUyWMwq1KblQtvC9lMzsLhBPQyxWELPqXh7WHNsv8sM56hfFhuCAACyOsd0&_nc_ht=scontent.fyhu1-1.fna&oh=b375cecd6626f77c85399fb1dbe49200&oe=5E4AF4E8" alt="Merci de votre achat!">

        <div class="confirmation__texteConfirmation" style="margin-bottom:15px">
            <p>Votre commande vous sera expédiée selon les modalités que vous avez choisies.
                <br/>N'hésitez pas à consulter
                notre service à la clientèle pour plus d'informations relatives à votre commande ou votre compte.</p>

            <p style="font-size:2rem">Votre numéro de confirmation est le&nbsp;:
                <br /><strong>7463-4846-2245</strong></p>
        </div>

        <div class="confirmation__sommaireCommande">
            <h2>Sommaire de votre commande</h2>
            <table style="margin-bottom:25px">
                <tbody>
                <hr>
                <tr>
                    <td>{{$infosPanier->getNombreTotalItems()}} articles</td>
                    <td><strong>{{$infosPanier->formaterPrix($infosPanier->getMontantSousTotal())}} $</strong></td>
                </tr>
                <tr>
                    <td>TPS 5%</td>
                    <td><strong>{{$infosPanier->formaterPrix($infosPanier->getMontantTPS())}} $</strong></td>
                </tr>
                <tr>
                    <td>Livraison standard</td>
                    <td><strong>0,00$</strong></td>
                </tr>
                <hr>
                <tr>
                    <td>Total</td>
                    <td><strong>{{$infosPanier->formaterPrix($infosPanier->getMontantTotal())}}$</strong></td>
                </tr>
                </tbody>
            </table>

            <div style="background-color:#FAFAFA;padding:15px;margin-bottom:15px" class="confirmation__sommaireCommande__adresseLivraison gris_pale">
                <h3>Adresse de livraison</h3>
                <span>{{$arrLivraison['prenom']['valeur']}}</span> <span>{{$arrLivraison['nom']['valeur']}}</span><br/>
                <span>{{$arrLivraison['adresse']['valeur']}}</span><br/>
                <span>{{$arrLivraison['ville']['valeur']}}</span> <span>{{$provinceLivraison}}</span><br/>
                <span>{{$arrLivraison['codePostal']['valeur']}}</span>
            </div>

            <div style="background-color:#F3F3F3;padding:15px;margin-bottom:15px" class="confirmation__sommaireCommande__adresseFacturation gris">
                <h3>Adresse de facturation</h3>
                <span>{{$arrFacturation['prenom']['valeur']}}</span> <span>{{$arrFacturation['nom']['valeur']}}</span><br/>
                <span>{{$arrFacturation['adresse']['valeur']}}</span><br/>
                <span>{{$arrFacturation['ville']['valeur']}}</span>, <span>{{$arrFacturation['province']['valeur']}}</span><br/>
                <span>{{$arrFacturation['codePostal']['valeur']}}</span>
            </div>

            <div style="background-color:#FAFAFA;padding:15px;margin-bottom:15px" class="confirmation__sommaireCommande__modePaiment gris_pale">
                <h3>Mode de paiement :<br/>
                    @if($arrFacturation['modePaiement']['valeur'] == 'carteCredit')
                        Carte de crédit
                    @else
                        PayPal
                    @endif
                </h3>

                @switch($arrFacturation['cartesCredit']['valeur'])
                    @case('visa')
                    @break
                    @case('masterCard')
                    @break
                    @case('amex')
                    @break
                @endswitch

                <span>{{$carteCache}}</span>

                <p>Expiration : {{$arrFacturation['moisCarte']['valeur']}}
                    / {{$arrFacturation['anneeCarte']['valeur']}}</p>
            </div>

            <div style="background-color:#F3F3F3;padding:15px;margin-bottom:15px" class="confirmation__sommaireCommande__informations gris">
                <h3>Informations</h3>
                <p>
                    <svg class="icone">
                        <use xlink:href="#icone_courriel"/>
                    </svg>
                    <strong>{{$arrClient->courriel}}</strong>
                </p>

                <p>
                    Numéro de téléphone : <strong>{{$numeroTelFormate}}</strong>
                </p>
            </div>
        </div>

        <div style="background-color:#FAFAFA;padding:15px;margin-bottom:15px" class="confirmation__commande">
            <h2>Commande</h2>
            <div class="panier__items section--espace">
                @foreach($livres as $livre)
                    <div class="panier__item">
                        <div class="panier__flex">
                            <div>
                                <span class="panier__itemTitre">
                                    <strong>{{$livre->livre->titre}}</strong>
                                </span><br/>
                                <span class="panier__itemAuteur">
                                    @foreach($livre->livre->getAuteurs() as $auteur)
                                        <span class="panier__itemAuteurNom">{{$auteur->getNomPrenom()}}</span>
                                    @endforeach
                                </span>
                            </div>
                            <div class="panier__itemQuantite">
                                <span>Quantité : {{$livre->quantite}}</span><br/>
                                <span class="panier__itemPrixTexte">Prix : {{$livre->formaterPrix($livre->getMontantTotal())}}$</span>
                            </div><br />
                            <div style="font-size:1.7rem" class="panier__itemPrixTotal">Total : <strong>{{$livre->formaterPrix($livre->getMontantTotal())}}$</strong></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection