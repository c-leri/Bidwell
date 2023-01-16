<div class="container">
        <svg class="circle-container" viewBox="2 -2 28 36">
            <linearGradient id="gradient">
                <stop class="stop1" offset="0%" />
                <stop class="stop2" offset="100%" />
            </linearGradient>
            <circle class="circle-container__background" r="16" cx="16" cy="16">
            </circle>
            <circle class="circle-container__progress" r="16" cx="16" cy="16" style="stroke-dashoffset: <?= $affichage ?>"
                shape-rendering="geometricPrecision">
            </circle>
            
            <!-- stroke-dashoffset = la progression de la barre (0 <= x < 75)-->
        </svg>

    <button id="encherebutton" onclick="encherir(event)"><span>Enchérir</span></button>

    
    <div class="temps">
            <p> Temps Restant </p>
            <p id="temps"><?= $tempsRestant ?></p>
        </div>

    <div class="prix">
        <div>
            <p> Prix de retrait </p>
            <p id="min"><?= $prixRetrait ?>€</p>

        </div>
        <div>

            <p>Prix Actuel</p>
            <p id="act"><?= $prixActuel ?>€ </p>

        </div>
        <div>

            <p> Prix de départ </p>
            <p id="max"><?= $prixDepart ?>€</p>
        </div>
    </div>

</div>