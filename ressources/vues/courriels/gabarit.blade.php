<!DOCTTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
</head>
<body>
<header >
    @include('courriels.fragments.entete')
</header>

<main>
    @yield('contenu')
</main>

<footer>
    @include('courriels.fragments.pied_de_page')
</footer>
</body>
</html>