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
                <div>
                    <input type="radio" id="Enchere" name="typeSelected" value="Enchere" style="display:none" checked>
                    <label for="annonces">Annonces</label>
                </div>

                <div>
                    <input type="radio" id="Utilisateur" name="typeSelected" value="Utilisateur" style="display:none">
                    <label for="utilisateurs">Utilisateurs</label>
                </div>
            </div>

            <div class="dropdown">
                <p> Trier par </p>
                <form action="">
                    <select name="tri" id="tri" onchange="showCustomer()">
                        <option value="creation">Date de création</option>
                        <option value="nom">Nom</option>
                        <option value="prix">Prix</option>
                    </select>
                </form>
            </div>
        </div>

        <aside>
            <?php include(__DIR__ . '/recherche-aside.viewpart.php') ?>

        </aside>

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

            <div class="annonces">
                <?php for ($i = 0; $i < 12; $i++) {
                    ?>


                <?php } ?>
            </div>

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

        </div>

    </main>

    <footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
    </footer>
</body>

</html>