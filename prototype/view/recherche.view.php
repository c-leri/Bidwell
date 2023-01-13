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
                        <option value="dateDepart">Date de création</option>
                        <option value="libelle">Nom</option>
                        <option value="prixDepart">Prix</option>
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

            <div class="annonces" id="annonces"> <!-- Ne pas retirer l'ID sinon JS cassé-->
                
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

<script src="../js/recherche.js"></script>