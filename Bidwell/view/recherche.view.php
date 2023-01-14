<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Site de vente aux enchères de particulier à particulier">
    <meta name="keywords" content="Bidwell, Bidwell.fr, Vente aux enchères, Vente aux enchères en ligne, Art, Bijouterie, Joaillerie, Mobilier, Mode, Bijoux, Sculptures, Monnaies, Pierres, Objets de collection">
    <meta name="author" content="Paul Sode, Gatien Caillet, Célestin Bouchet, Antoine Vuillet, Clément Mazet, Hippolyte Chauvin">
    <title>Consultation d'un article</title>
    <link rel="stylesheet" href="../../view/design/styleGeneral.css">
    <link rel="stylesheet" href="../../view/design/styleMenu.css">
    <link rel="stylesheet" href="../../view/design/styleFooter.css">
    <link rel="stylesheet" href="../../view/design/styleRecherche.css">
    <link rel="icon" type="image/x-icon" href="../../view/design/img/favicon.ico">


</head>

<body>
    <!-- Menu -->
    <header>
        <?php include(__DIR__ . '/menu.viewpart.php') ?>
    </header>

    <main class="recherche">
        <div class="top">
            <h2> Filtrer </h2>

            <div class="choix">
                <div>
                    <input type="radio" id="Enchere" name="typeSelected" value="Enchere" style="display:none" onclick="showItems()" checked>
                    <label for="Enchere">Annonces</label>
                </div>

                <div>
                    <input type="radio" id="Utilisateur" name="typeSelected" value="Utilisateur" style="display:none" onclick="showItems()">
                    <label for="Utilisateur">Utilisateurs</label>
                </div>
            </div>

            <div class="dropdown">
                <p> Trier par </p>
                <form action="">
                    <select name="tri" id="tri" onchange="showItems()">
                        <option value="date">Date de création</option>
                        <option value="nom">Nom</option>
                        <option value="prix">Prix</option>
                    </select>
                </form>
            </div>
        </div>
        <div class="center">
            <aside>
                <?php include(__DIR__ . '/recherche-aside.viewpart.php') ?>

            </aside>
            <div class="annonces" id='annonces'> <!-- Retire id='annonces' pour les annonces de base-->
                <?php for ($i = 0; $i < 12; $i++) : ?>

                    <article>
                        <a href="consultation.ctrl.php">
                            <img src="../../view/design/img/default_image.png" alt="">
                        </a>
                        <div class="left">
                            <h1>Titre--------------------</h1>
                            <h2>Catégorie--------------------</h2>
                            <ul>
                                <li> Temps restant</li>
                                <li> Prix actuel</li>
                                <li> Par JpDUpont</li>
                            </ul>
                        </div>
                        <p class="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. In libero risus, luctus vitae erat eu, bibendum malesuada tellus. Suspendisse at ante a leo laoreet iaculis. Phasellus sollicitudin egestas diam. Donec non suscipit sapien. Nam sit amet rhoncus enim. Morbi euismod lacus in pellentesque viverra. Cras nec justo porta, elementum turpis eu, elementum ante. Quisque vitae mauris eu tortor commodo eleifend. Pellentesque ac ipsum elementum lorem cursus pharetra convallis sed risus. Cras eu ex aliquet, convallis lectus ac, varius odio. Mauris sit amet sapien sed erat ullamcorper congue eget at eros.</p>
                    </article>

                <?php endfor; ?>
            </div>
        </div>
        <div class="liste">
            <div class="numPage">
                <button id="back">
                    < </button>
                        <button id="first">1</button>
                        <button id="second">2</button>
                        <button id="third">3</button>
                        <button id="fourth">4</button>
                        <button id="fifth">5</button>
                        --
                        <button id="max">X</button>
                        <button id="next">></button>
            </div>

            <div class="annonces" id="annonces"> <!-- Ne pas retirer l'ID sinon JS cassé-->

                </div>

        </div>

    </main>

    <footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
    </footer>
</body>

</html>

<script src="../../js/recherche.js"></script>