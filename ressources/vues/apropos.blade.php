@extends('gabarit')

{{--cible la section contenu dans le gabarit pour venir insérer le contenu personnalisé de a propos--}}
@section('contenu')
    <div class="conteneur">
        <h1>À propos</h1>

        <p>Site pour la librairie Traces - Cégep de Sainte-Foy, Techniques d'intégrations multimédia 2019.</p>
        <br />
        <ul>
            <li><b>Alexandrine C. Sevigny :</b> Mandat C</li>
            <li><b>Camille Dion-Bolduc :</b> Mandat A</li>
            <li><b>Emie Pelletier :</b> Mandat B</li>
        </ul>
        <br/><br/>
    </div>

@endsection

