@extends('gabarit')

@section('contenu')
    <div class="conteneur">
        <h1 class="section-aucunEspace">Résultats de recherche</h1>

        <p class="h3">Mot-clé recherché : "{{$valeurChamp}}"</p>

        <div class="section--espace">

            @if($resultats !== '')
                <ul>
                    @foreach($resultats as $resultat)

                        @switch ($valeurListe)
                            @case('auteur')
                            <li>{{$resultat->prenom}} {{$resultat->nom}}</li>
                            @break
                            @case('isbn')
                            <li>{{$resultat->isbn}}</li>
                            @break
                            @case('titre')
                            <li>{{$resultat->formaterTitre()}}</li>
                            @break
                            @case('sujet')
                            <li>{{$resultat->mots_cles}}</li>
                            @break
                        @endswitch

                    @endforeach
                </ul>

            @else

                <p>Aucun résultat ne correspond à vos recherches.</p>

            @endif

        </div>

    </div>
@endsection