<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Site de vente aux enchères de particulier à particulier">
    <meta name="keywords" content="Bidwell, Bidwell.fr, Vente aux enchères, Vente aux enchères en ligne, Art, Bijouterie, Joaillerie, Mobilier, Mode, Bijoux, Sculptures, Monnaies, Pierres, Objets de collection">
    <meta name="author" content="Paul Sode, Gatien Caillet, Célestin Bouchet, Antoine Vuillet, Clément Mazet, Hippolyte Chauvin">
    <title>Consultation d'un article</title>
    <link rel="stylesheet" href="../View/design/styleGeneral.css">
    <link rel="stylesheet" href="../View/design/styleMenu.css">
    <link rel="stylesheet" href="../View/design/styleFooter.css">
    <link rel="stylesheet" href="../View/design/styleRecherche.css">
    <link rel="icon" type="image/x-icon" href="../View/design/img/favicon.ico">


</head>

<body>
    <!-- Menu -->
    <header>
        <?php include __DIR__ . ($connected ? '/menu_connecte.viewpart.php' : '/menu.viewpart.php') ?>
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
                        <option value="dateAsc">Date de création ↑</option>
                        <option value="dateDesc">Date de création ↓</option>
                        <option value="nomAsc">Nom ↑</option>
                        <option value="nomDesc">Nom ↓</option>
                        <option value="prixAsc">Prix (croissant)</option>
                        <option value="prixDesc">Prix (décroissant)</option>
                    </select>
                </form>
            </div>
        </div>
        <div class="center">
            <aside>
                <?php include(__DIR__ . '/recherche-aside.viewpart.php') ?>

            </aside>
            <div class="annonces" id='annonces'>
                
            </div>
        </div>
    </main>

    <footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
    </footer>
</body>

</html>

<script src="../JS/recherche.js"></script>