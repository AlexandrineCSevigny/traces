<meta charset="utf-8"/>
@if( isset($_GET['controleur']) )
    @switch($_GET['controleur'])
        @case('site')
        @switch($_GET['action'])
            @case('accueil')
            <title>Traces - Toute l’histoire des Amériques</title>
            <meta name="description"
                  content="Traces, éditeur et libraire - La référence en livres d’histoires du Québec, du Canada et de l’Amérique du Nord">
            <meta name="keywords" content="Traces, éditeur, libraire, histoire, littérature">
            <meta name="author" content="Alexandrine C. Sévigny, Camille Dion-Bolduc">
            @break
            @case('aproppos')
            <title>À propos de Traces - Toute l’histoire des Amériques</title>
            <meta name="description"
                  content="À propos de Traces, un éditeur et libraire - La référence en livres d’histoires du Québec, du Canada et de l’Amérique du Nord">
            <meta name="keywords" content="Traces, éditeur, libraire, histoire, littérature, à propos, contact">
            <meta name="author" content="Alexandrine C. Sévigny, Camille Dion-Bolduc et Emie Pelletier">
            @break
        @endswitch
        @break
        @case('livre')
        @switch($_GET['action'])
            @case('index')

            <title> {{$filAriane[1]['titre']}} - Traces</title>

            @switch($filAriane[1]['titre'])
                @case ('Catalogue')
                @if(count($filAriane)>2)
                    <meta name="description"
                          content="Livres appartenant à la catégorie «{{$filAriane[2]['titre']}}» disponibles à la vente de l'éditeur et libraire Traces.">
                @else
                    <meta name="description"
                          content="Catalogue complet disponible à la vente de l'éditeur et libraire Traces.">
                @endif
                @break
                @case ('Nouveautés')
                <meta name="description"
                      content="Nouveaux livres disponibles à la vente de l'éditeur et libraire Traces.">
                @break

            @endswitch

            <meta name="keywords" content="Traces, éditeur, libraire, catalogue, livre, produit">
            <meta name="author" content="Emie Pelletier">
            @break

            @case('fiche')
            <title>{{$filAriane[2]['titre']}}- {{$filAriane[1]['titre']}} - Traces</title>
            <meta name="description"
                  content="Fiche produit du livre {{$livre->titre}}">
            <meta name="keywords" content="{{$livre->mots_cles}}">
            <meta name="author" content="Alexandrine C. Sévigny">
            @break
        @endswitch
        @break

        @case('panier')
        <title>Panier d'achats - Traces</title>
        <meta name="description"
              content="Panier d'achats de l'éditeur et libraire Traces">
        <meta name="keywords" content="panier traces librairie commande vente">
        <meta name="author" content="Alexandrine C. Sévigny">
        @break

        @default
        <title>Traces - Toute l’histoire des Amériques</title>
        <meta name="description"
              content="Traces, éditeur et libraire - La référence en livres d’histoires du Québec, du Canada et de l’Amérique du Nord">
        <meta name="keywords" content="Traces, éditeur, libraire, histoire, littérature">
        <meta name="author" content="Alexandrine C. Sévigny, Camille Dion-Bolduc et Emie Pelletier">
    @endswitch
@else
    <title>Traces - Toute l’histoire des Amériques</title>
    <meta name="description"
          content="Traces, éditeur et libraire - La référence en livres d’histoires du Québec, du Canada et de l’Amérique du Nord">
    <meta name="keywords" content="Traces, éditeur, libraire, histoire, littérature">
    <meta name="author" content="Alexandrine C. Sévigny, Camille Dion-Bolduc et Emie Pelletier">
@endif

<meta name="viewport" content="width=device-width"/>
<link rel="icon" type="image/png" sizes="32x32" href="liaisons/images/autres/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="liaisons/images/autres/favicon-16x16.png">
<link rel="stylesheet" type="text/css" href="./liaisons/css/styles.css"/>