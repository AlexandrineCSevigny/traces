@if( isset($_GET['controleur']) )
    @if( $_GET['controleur'] == 'livraison' || $_GET['controleur'] == 'facturation' || $_GET['controleur'] == 'validation' )
        <div class="gris">
        <div class="footer__secondaire conteneur">
            <div class="securite">
                <p>
                    <a href="#" class="securite__lien lienAnime">Conditions d'utilisations</a>,
                    <a href="#" class="securite__lien lienAnime">Politique
                        de confidentialité</a>.
                    <a href="#" class="securite__lien lienAnime">Politique de retour</a>.
                    <span>Certificat de sécurité.</span>
                    <svg class="securite__icone icone">
                        <use xlink:href="#icone_certificat"/>
                    </svg>
                </p>
            </div>
            <div class="copyright">
                <p>
                    Alexandrine C. Sevigny, Camille Dion-Bolduc & Emie Pelletier © 2019 - Tous droits réservés -
                    Librairie
                    Traces pour le Cégep de Sainte-Foy
                </p>
            </div>
        </div>
    </div>
    @else
        <div class="gris">
            <div class="footer__principal conteneur">
                <div class="menu__logo">
                    <a class="menu__logoLien" href="index.php?controleur=site&action=accueil">
                        <span class="screen-reader-only">Traces</span>
                        <svg class="menu__logoSVG logo icone">
                            <use xlink:href="#logo_traces"/>
                        </svg>
                    </a>
                </div>
                <div class="carte">
                    <p class="carte__titre h3"> Trouver un magasin</p>
                    <div class="carte__image">
                        <span class="carte__imageLibelle screen-reader-only">Nos adresses de magasins</span>
                        <span class="carte__imageSVG"></span>
                    </div>
                </div>
                <div class="media">
                    <div class="mediaSociaux">
                        <p class="mediaSociaux__titre h3">Suivez-nous</p>
                        <a class="mediaSociaux__facebook" href="#">
                            <span class="screen-reader-only">Facebook</span><span class="icone facebook"></span>
                        </a>
                        <a class="mediaSociaux__instagram" href="#">
                            <span class="screen-reader-only">Instagram</span><span class="icone instagram"></span>
                        </a>
                        <a class="mediaSociaux__twitter" href="#">
                            <span class="screen-reader-only">Twitter</span><span class="icone twitter"></span>
                        </a>
                    </div>
                    <div class="contact">
                        <p class="contact__titre h3">Nous joindre</p>
                        <div>
                            <svg class="contact__icone icone">
                                <use xlink:href="#icone_telephone"/>
                            </svg>
                            <span class="contact__texte">
                    1-800-999-8787
                </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="blanc">
            <div class="footer__secondaire conteneur">
                <div class="securite">
                    <p>
                        <a href="#" class="securite__lien lienAnime">Conditions d'utilisations</a>,
                        <a href="#" class="securite__lien lienAnime">Politique
                            de confidentialité</a>.
                        <a href="#" class="securite__lien lienAnime">Politique de retour</a>.
                        <span>Certificat de sécurité.</span>
                        <svg class="securite__icone icone">
                            <use xlink:href="#icone_certificat"/>
                        </svg>
                    </p>
                </div>
                <div class="copyright">
                    <p>
                        Alexandrine C. Sevigny, Camille Dion-Bolduc & Emie Pelletier © 2019 - Tous droits réservés -
                        Librairie
                        Traces pour le Cégep de Sainte-Foy
                    </p>
                </div>
            </div>
        </div>
    @endif
@else
    <div class="gris">
        <div class="footer__principal conteneur">
            <div class="menu__logo">
                <a class="menu__logoLien" href="index.php?controleur=site&action=accueil">
                    <span class="screen-reader-only">Traces</span>
                    <svg class="menu__logoSVG logo icone">
                        <use xlink:href="#logo_traces"/>
                    </svg>
                </a>
            </div>
            <div class="carte">
                <p class="carte__titre h3"> Trouver un magasin</p>
                <div class="carte__image">
                    <span class="carte__imageLibelle screen-reader-only">Nos adresses de magasins</span>
                    <span class="carte__imageSVG"></span>
                </div>
            </div>
            <div class="media">
                <div class="mediaSociaux">
                    <p class="mediaSociaux__titre h3">Suivez-nous</p>
                    <a class="mediaSociaux__facebook" href="#">
                        <span class="screen-reader-only">Facebook</span><span class="icone facebook"></span>
                    </a>
                    <a class="mediaSociaux__instagram" href="#">
                        <span class="screen-reader-only">Instagram</span><span class="icone instagram"></span>
                    </a>
                    <a class="mediaSociaux__twitter" href="#">
                        <span class="screen-reader-only">Twitter</span><span class="icone twitter"></span>
                    </a>
                </div>
                <div class="contact">
                    <p class="contact__titre h3">Nous joindre</p>
                    <div>
                        <svg class="contact__icone icone">
                            <use xlink:href="#icone_telephone"/>
                        </svg>
                        <span class="contact__texte">
                    1-800-999-8787
                </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="blanc">
        <div class="footer__secondaire conteneur">
            <div class="securite">
                <p>
                    <a href="#" class="securite__lien lienAnime">Conditions d'utilisations</a>,
                    <a href="#" class="securite__lien lienAnime">Politique
                        de confidentialité</a>.
                    <a href="#" class="securite__lien lienAnime">Politique de retour</a>.
                    <span>Certificat de sécurité.</span>
                    <svg class="securite__icone icone">
                        <use xlink:href="#icone_certificat"/>
                    </svg>
                </p>
            </div>
            <div class="copyright">
                <p>
                    Alexandrine C. Sevigny, Camille Dion-Bolduc & Emie Pelletier © 2019 - Tous droits réservés -
                    Librairie
                    Traces pour le Cégep de Sainte-Foy
                </p>
            </div>
        </div>
    </div>
@endif
