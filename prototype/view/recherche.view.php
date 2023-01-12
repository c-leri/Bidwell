<!DOCTYPE html>
<html>

<head>
    <html lang="fr">
    <meta charset="utf-8">
    <meta name="description" content="Site de vente aux enchères de particulier à particulier">
    <meta name="keywords"
        content="Bidwell, Bidwell.fr, Vente aux enchères, Vente aux enchères en ligne, Art, Bijouterie, Joaillerie, Mobilier, Mode, Bijoux, Sculptures, Monnaies, Pierres, Objets de collection">
    <meta name="author"
        content="Paul Sode, Gatien Caillet, Célestin Bouchet, Antoine Vuillet, Clément Mazet, Hippolyte Chauvin">
    <title>Consultation d'un article</title>
    <link rel="stylesheet" href="../view/design/styleGeneral.css">
    <link rel="stylesheet" href="../view/design/styleMenu.css">
    <link rel="stylesheet" href="../view/design/styleFooter.css">
    <link rel="stylesheet" href="../view/design/styleRecherche.css">
    <link rel="icon" type="image/x-icon" href="../view/design/img/favicon.ico">
    <script src="../js/recherche.js"></script>

</head>

<body>
    <!-- Menu -->
    <header>
        <?php include(__DIR__ . '/menu.viewpart.php') ?>
    </header>

    <main>
        <div class="top">
            <h2> Filtrer </h2>

            <div class="choix">
                <button id="annonces" onclick=""> Annonces </button>
                |
                <button id="utilisateurs" onclick=""> Utilisateurs </button>
            </div>

            <div class="dropdown">
                <p> Trier par </p>
                <button class="dropbtn">+</button>
                <div class="dropdown-content">
                    <button id="date" onclick="">Date de création</button>
                    <button id="nom" onclick="">Nom</button>
                    <button id="prix" onclick="">Prix de départ</button>
                </div>
            </div>
        </div>

        <aside>
            <?php include(__DIR__ . '/recherche-aside.viewpart.php') ?>

        </aside>

        <div class="liste">
            <div class="numPage">
                <button id="back"> < </button>
                        <button id="first">1</button>
                        <button id="second">2</button>
                        <button id="third">3</button>
                        <button id="fourth">4</button>
                        <button id="fifth">5</button>
                        --
                        <button id="max">X</button>
                        <button id="next">></button>
            </div>

            <div class="annonces">
                <?php for ($i = 0; $i < 12; $i++) {
                    ?>

                    <article>
                        <a href="consultation.ctrl.php">
                            <img src="../view/design/img/default_image.png" alt="">
                        </a>
                        <h1>Ceci est un titre</h1>
                        <div class="variablesAnnonce">
                            <p class="categorie">catégorie</p>
                            <p class="temps-restant">temps restant</p>
                            <p class="prix-actuel">prix actuel</p>
                            <p class="createur">Utilisateur</p>
                            <p>Description</p>
                            <p class="description">Ceci est un lorem ipsum.</p>
                        </div>
                    </article>

                <?php } ?>
            </div>

            <div class="numPage">
                <button id="back"> < </button>
                        <button id="first">1</button>
                        <button id="second">2</button>
                        <button id="third">3</button>
                        <button id="fourth">4</button>
                        <button id="fifth">5</button>
                        --
                        <button id="max">X</button>
                        <button id="next">></button>
            </div>

        </div>

    </main>

    <footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
    </footer>
</body>

</html>