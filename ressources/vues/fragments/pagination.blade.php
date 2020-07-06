<div class="pagination">


    <!-- Si on est pas sur la première page et s'il y a plus d'une page -->
    @if ($numeroPage > 0)
        <a class="pagination__lien" href="{!! $urlPagination . "&page=" . 0  !!}">Premier</a>
    @else
        <span class="pagination__lien" style="color:#999">Premier</span> <!-- Bouton premier inactif -->
    @endif

    @if ($numeroPage > 0)
        <a class="pagination__lien" href="{!! $urlPagination . "&page=" . (htmlspecialchars($numeroPage) - 1) !!}">
            <span class="visuallyhidden">Précédent</span>
            <
        </a>
    @else
    <!-- Bouton précédent inactif -->
        <p class="pagination__lien" style="color:#999">
            <span class="visuallyhidden">Précédent</span>
            <
        </p>
    @endif


<!-- Statut de progression: page 9 de 99 -->
    <span class="pagination__lien">Page <b> {{$numeroPage + 1}} </b>  de {{$nombreTotalPages + 1}}</span>


    <!-- Si on est pas sur la dernière page et s'il y a plus d'une page -->
    @if ($numeroPage < $nombreTotalPages)
        <a class="pagination__lien" href="{!! $urlPagination . "&page=" . (htmlspecialchars($numeroPage) + 1)  !!}">
            <span class="visuallyhidden">Suivant</span>
            >
        </a>
    @else
    <!-- Bouton suivant inactif -->
        <span class="pagination__lien" style="color:#999">
         <span class="visuallyhidden">Suivant</span>
            >
        </span>
    @endif

    @if ($numeroPage < $nombreTotalPages)
        <a class="pagination__lien" href="{!! $urlPagination . "&page=" . htmlspecialchars($nombreTotalPages) !!}">Dernier</a>
    @else
        <span class="pagination__lien" style="color:#999">Dernier</span>
        <!-- Bouton dernier inactif -->
    @endif

</div>
