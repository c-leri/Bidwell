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
    <link rel="icon" type="image/x-icon" href="../View/design/img/favicon.ico">

</head>


<body>
    <!-- Menu -->
    <header>
        <?php
            include isset($_SESSION['login']) ? __DIR__ . '/menu_connecte.viewpart.php' : __DIR__ . '/menu.viewpart.php';
            include __DIR__ . '/sous-menu_categorie.viewpart.php';
        ?>
    </header>
    <main class="pagePrincipale">
        </div>
        <h2>Nouvelles enchères</h2>
        <div class="ventesPopulaires" id="new">

        </div>

        <hr>

        <h2>Enchères bientôt terminées</h2>
        <div class="ventesPopulaires" id="old">

        </div>
    </main>
    <footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
    </footer>
</body>


</html>

<script src="../JS/main.js"></script>