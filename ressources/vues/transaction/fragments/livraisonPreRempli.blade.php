<fieldset>
    <legend class="visuallyhidden">Individu</legend>

    <!-----------   Prénom  ----------->
    <div class="ctnForm">
        <label for="prenom">Prénom</label>
        <input type="text" name="prenom" id="prenom" required class="livraison__prenom"
               value="{{$adresseClient->prenom}}"
               pattern="^[a-zA-ZÀ-ÿ' -]+$"
               title="Utilisez des lettres, des traits d'unions ou des espaces pour composer votre prénom."
               aria-required="true"/>


        <!----   Gestion des messages d'erreur  ---->
        <p class="validation__message" aria-atomic="true" aria-live="assertive">
            @isset($arrLivraison['prenom']['message'])
                @isset($arrLivraison['prenom']["champValide"])
                    @if($arrLivraison['prenom']["champValide"] == 1)
                        <svg class="formulaire__validation">
                            <use xlink:href="#icone_form_valide"/>
                        </svg>
                    @else
                        <svg class="formulaire__validation">
                            <use xlink:href="#icone_form_erreur"/>
                        </svg>
                    @endif
                @endisset
                {{$arrLivraison['prenom']['message']}}
            @endisset
        </p>
    </div>

    <!-----------   Nom  ----------->
    <div class="ctnForm">
        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom" required class="livraison__nom"
               pattern="^[a-zA-ZÀ-ÿ' -]+$"
               title="Utilisez des lettres, des traits d'unions ou des espaces pour composer votre nom."
               aria-required="true"
               value="{{$adresseClient->nom}}"/>

        <!----   Gestion des messages d'erreur  ---->
        <p class="validation__message" aria-atomic="true" aria-live="assertive">
            @isset($arrLivraison['nom']['message'])
                @isset($arrLivraison['nom']["champValide"])
                    @if($arrLivraison['nom']["champValide"] == 1)
                        <svg class="formulaire__validation">
                            <use xlink:href="#icone_form_valide"/>
                        </svg>
                    @else
                        <svg class="formulaire__validation">
                            <use xlink:href="#icone_form_erreur"/>
                        </svg>
                    @endif
                @endisset
                {{$arrLivraison['nom']['message']}}
            @endisset
        </p>

    </div>
</fieldset>

<!-----------   Adresse ----------->
<div class="ctnForm">
    <label for="adresse">Adresse</label>
    <input type="text" name="adresse" id="adresse" required class="livraison__adresse"
           value="{{$adresseClient->adresse}}"
           pattern="[0-9]{1,6} [- 'a-zA-ZÀ-ÿ0-9]+"
           title="Utilisez des lettres, des chiffres, des traits d'unions ou des espaces pour composer votre adresse."
           aria-required="true"/>


    <!----   Gestion des messages d'erreur  ---->
    <p class="validation__message" aria-atomic="true" aria-live="assertive">
        @isset($arrLivraison['adresse']['message'])
            @isset($arrLivraison['adresse']["champValide"])
                @if($arrLivraison['adresse']["champValide"] == 1)
                    <svg class="formulaire__validation">
                        <use xlink:href="#icone_form_valide"/>
                    </svg>
                @else
                    <svg class="formulaire__validation">
                        <use xlink:href="#icone_form_erreur"/>
                    </svg>
                @endif
            @endisset
            {{$arrLivraison['adresse']['message']}}
        @endisset
    </p>
</div>


<!-----------  Ville ----------->
<div class="ctnForm">
    <label for="ville">Ville</label>
    <input type="text" name="ville" id="ville" required class="livraison__ville"
           value="{{$adresseClient->ville}}"
           pattern="[a-zA-ZÀ-ÿ' -]+"
           title="Utilisez des lettres ou des espaces pour composer votre ville."
           aria-required="true"/>


    <!----   Gestion des messages d'erreur  ---->
    <p class="validation__message" aria-atomic="true" aria-live="assertive">
        @isset($arrLivraison['ville']['message'])
            @isset($arrLivraison['ville']["champValide"])
                @if($arrLivraison['ville']["champValide"] == 1)
                    <svg class="formulaire__validation">
                        <use xlink:href="#icone_form_valide"/>
                    </svg>
                @else
                    <svg class="formulaire__validation">
                        <use xlink:href="#icone_form_erreur"/>
                    </svg>
                @endif
            @endisset
            {{$arrLivraison['ville']['message']}}
        @endisset
    </p>
</div>

<!-----------   Province  ----------->
<div class="ctnForm">
    <label for="province">Province</label>
    <div class="select__conteneur">

        <select name="province" id="province" required aria-required="true" class="livraison__province">
            <option value="">Sélectionner une province</option>
            <option value="AB" @if($adresseClient->abbr_province == 'AB') selected @endif>Alberta
            </option>
            <option value="CB" @if($adresseClient->abbr_province == 'CB') selected @endif>
                Colombie-Britannique
            </option>
            <option value="PE" @if($adresseClient->abbr_province == 'PE') selected @endif>
                Île-du-Prince-Édouard
            </option>
            <option value="MB" @if($adresseClient->abbr_province == 'MB') selected @endif>Manitoba
            </option>
            <option value="NB" @if($adresseClient->abbr_province == 'NB') selected @endif>
                Nouveau-Brunswick
            </option>
            <option value="NS" @if($adresseClient->abbr_province == 'NS') selected @endif>
                Nouvelle-Écosse
            </option>
            <option value="ON" @if($adresseClient->abbr_province == 'ON') selected @endif>Ontario
            </option>
            <option value="QC" @if($adresseClient->abbr_province == 'QC') selected @endif>Québec
            </option>
            <option value="SK" @if($adresseClient->abbr_province == 'SK') selected @endif>
                Saskatchewan
            </option>
            <option value="NL" @if($adresseClient->abbr_province== 'NL') selected @endif>
                Terre-Neuve
                et
                Labrador
            </option>
            <option value="NT" @if($adresseClient->abbr_province == 'NT') selected @endif>
                Territoires
                du
                Nord-Ouest et Nunavut
            </option>
            <option value="YT" @if($adresseClient->abbr_province == 'YT') selected @endif>Yukon
            </option>
        </select>
    </div>


    <!----   Gestion des messages d'erreur  ---->
    <p class="validation__message" aria-atomic="true" aria-live="assertive">
        @isset($arrLivraison['province']['message'])
            @isset($arrLivraison['province']["champValide"])
                @if($arrLivraison['province']["champValide"] == 1)
                    <svg class="formulaire__validation">
                        <use xlink:href="#icone_form_valide"/>
                    </svg>
                @else
                    <svg class="formulaire__validation">
                        <use xlink:href="#icone_form_erreur"/>
                    </svg>
                @endif
            @endisset
            {{$arrLivraison['province']['message']}}
        @endisset
    </p>
</div>


<!-----------   Code postal  ----------->
<div class="ctnForm">
    <label for="codePostal">Code postal<span class="messageInfo"> (A0A 0A0)</span>
    </label>
    <input type="text" name="codePostal" id="codePostal" required aria-required="true"
           value="{{$adresseClient->code_postal}}"
           pattern="[A-Za-z][0-9][A-Za-z] [0-9][A-Za-z][0-9]" title="A0A 0A0" maxlength="8"
           class="input--moyen livraison__codePostal">


    <!----   Gestion des messages d'erreur  ---->
    <p class="validation__message" aria-atomic="true" aria-live="assertive">
        @isset($arrLivraison['codePostal']['message'])
            @isset($arrLivraison['codePostal']["champValide"])
                @if($arrLivraison['codePostal']["champValide"] == 1)
                    <svg class="formulaire__validation">
                        <use xlink:href="#icone_form_valide"/>
                    </svg>
                @else
                    <svg class="formulaire__validation">
                        <use xlink:href="#icone_form_erreur"/>
                    </svg>
                @endif
            @endisset
            {{$arrLivraison['codePostal']['message']}}
        @endisset
    </p>
</div>
