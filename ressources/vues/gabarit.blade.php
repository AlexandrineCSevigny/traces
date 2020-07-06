<!DOCTYPE html>
<html lang="en">
<head>
    @include('fragments.head')
</head>

<body>
<a href="#contenu" class="sauter visuallyhidden focusable">Allez au contenu</a>
<a href="#contenu" class="boutonRetourHaut" id="boutonRetourHaut" title="Aller en haut">
    <span class="visuallyhidden">Retour vers le haut de la page</span>
</a>
<header class="header cf" role="banner">
    @include('fragments.entete')
</header>

<noscript class="conteneur">
    Le Javascript n'est pas activé sur la page, pour une meilleure expérience nous vous recommandons de l'activer.
</noscript>

<main id="contenu" role="main">
    {{--cède aux héritiers à personnaliser le contenu --}}
    @yield('contenu')
</main>

<footer class="footer" role="contentinfo">
    @include('fragments.pieddepage')
</footer>

<div id="sprite">
    <?php echo file_get_contents("liaisons/symbol/svg/sprite.symbol.svg"); ?>
</div>

<!-- CDN v2018 -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>

<script>window.jQuery || document.write('<script src=../node_modules/jquery/dist/jquery.min.js">\x3C/script>')</script>

<!-- On importe toutes les classes de l'application et on instancie l'application dans app.js. -->
<script src="../node_modules/requirejs/require.js" data-main="liaisons/js/app.js"></script>

<script src="./../public/liaisons/js/messagesValidationClient.js"></script>

</body>
</html>