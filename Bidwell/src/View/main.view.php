<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Site de vente aux enchères de particulier à particulier">
    <meta name="keywords" content="Bidwell, Bidwell.fr, Vente aux enchères, Vente aux enchères en ligne, Art, Bijouterie, Joaillerie, Mobilier, Mode, Bijoux, Sculptures, Monnaies, Pierres, Objets de collection">
    <meta name="author" content="Paul Sode, Gatien Caillet, Célestin Bouchet, Antoine Vuillet, Clément Mazet, Hippolyte Chauvin">
    <title>Main</title>
    <link rel="stylesheet" href="../View/design/styleGeneral.css">
    <link rel="stylesheet" href="../View/design/styleMenu.css">
    <link rel="stylesheet" href="../View/design/styleSous-Menu_Categorie.css">
    <link rel="stylesheet" href="../View/design/styleFooter.css">
    <link rel="stylesheet" href="../View/design/stylePagePrincipale.css">
    <link rel="stylesheet" href="../View/design/styleCookies.css">
    <link rel="icon" type="image/x-icon" href="../View/design/img/favicon.ico">

</head>


<body>
    <!-- Menu -->
    <header>
        <?php
        session_start();
        if (isset($_SESSION['login'])) {
            include(__DIR__ . '/menu_connecte.viewpart.php');
        } else {
            include(__DIR__ . '/menu.viewpart.php');
        }
        include(__DIR__ . '/sous-menu_categorie.viewpart.php')
        ?>
    </header>
    <main class="pagePrincipale">
        <!-- Test de cookies -->
        <div id="fond-cookies">
            <div id="pop-up-cookies">

                <div class="info-cookies">
                    <p>Notre site conserve des données personnelles permettant de vous identifier. Par conséquent, si vous n'acceptez pas les cookies vous ne pourrez pas profiter de toutes les fonctionnalités de notre site.
                    </p>
                    <ul>
                        <li> Nous permettre de garder vos données personnelles.
                            <input type="checkbox" name="okCookies">
                        </li>
                    </ul>
                    <p>
                        Certains cookies sont nécessaires à des fins techniques, ils sont donc dispensés de consentement.
                    </p>
                </div>

                <div class="d-flex">
                    <button class="valider-cookies" onclick="stop()">Valider votre choix</button>
                    <button class="refuser-cookies" onclick="stop()">Tout refuser</button>
                </div>
            </div>
        </div>

        <div id="slider">
            <a href="#"><img src="../View/design/img/default_image.png" alt="Besoin d'aide" id="slide" style="background-repeat: repeat"></a>
            <div id="precedent" onclick="ChangeSlide(-1)">
                < </div>
                    <div id="suivant" onclick="ChangeSlide(1)">></div>
            </div>

            <hr>
            <script src="../JS/slider.js"></script>
        </div>
        <h2>Nouvelles enchères</h2>
        <div class="ventesPopulaires" id="new">

        </div>

        <hr>

        <h2>Enchères bientôt terminées</h2>
        <div class="ventesPopulaires" id="old">

        </div>
        <script src="../JS/main.js"></script>
    </main>
    <footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
    </footer>
</body>


</html>