<div id="fond-cookies">
    <div id="pop-up-cookies">

        <div class="info-cookies">
            <p>Notre site conserve des données personnelles permettant de vous identifier. Par conséquent, si vous n'acceptez pas les cookies vous ne pourrez pas profiter de toutes les fonctionnalités de notre site.
            </p>
            <ul>
                <li> Nous permettre de garder vos données personnelles.
                    <input type="checkbox" id="okCookies" />
                </li>
            </ul>
            <p>
                Certains cookies sont nécessaires à des fins techniques, ils sont donc dispensés de consentement.
            </p>
        </div>

        <div class="d-flex">
            <input id="valider-cookies" type="submit" onclick="actionValiderCookies('infosPersonnelles', 'accepte')" value="Valider votre choix" disabled />
            <button class="refuser-cookies" onclick="stop()">Refuser</button>
        </div>
    </div>
</div>