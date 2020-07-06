<div class="checkbox">
    <input id="adresseLivraison" name="adresseLivraison" type="checkbox" aria-required="true"
           class="visuallyhidden checkbox__input" checked/>
    <label for="adresseLivraison" class="checkbox__libelle">
        Utiliser mon adresse de livraison pour l'adresse de facturation
    </label>
</div>

<span>{{$arrLivraison['prenom']['valeur']}}</span>
<span>{{$arrLivraison['nom']['valeur']}}</span>
<p>{{$arrLivraison['adresse']['valeur']}}</p>
<p>{{$arrLivraison['ville']['valeur']}}</p>
<p>{{$arrLivraison['province']['valeur']}}</p>
<p>{{$arrLivraison['codePostal']['valeur']}}</p>

