<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Site de vente aux enchères de particulier à particulier">
    <meta name="keywords"
        content="Bidwell, Bidwell.fr, Vente aux enchères, Vente aux enchères en ligne, Art, Bijouterie, Joaillerie, Mobilier, Mode, Bijoux, Sculptures, Monnaies, Pierres, Objets de collection">
    <meta name="author"
        content="Paul Sode, Gatien Caillet, Célestin Bouchet, Antoine Vuillet, Clément Mazet, Hippolyte Chauvin">
    <title>À Propos</title>
    <link rel="stylesheet" href="../View/design/styleGeneral.css">
    <link rel="stylesheet" href="../View/design/styleMenu.css">
    <link rel="stylesheet" href="../View/design/styleFooter.css">
    <link rel="stylesheet" href="../View/design/styleApropos.css">
    <link rel="icon" type="image/x-icon" href="../View/design/img/favicon.ico">
</head>

<body>
    <!-- Menu -->
    <header>
    <?php include isset($_SESSION['login']) ? __DIR__ . '/menu_connecte.viewpart.php' : __DIR__ . '/menu.viewpart.php'; ?>
    </header>
    <main class="Apropos">
        <article>
            <img src="../View/design/img/Antoine.jpg" alt="Antoine">
            <h1>Antoine Vuillet</h1>
            <p>Responsable qualité</p>
        </article>
        <article>
            <img src="../View/design/img/Celestin.jpg" alt="Célestin">
            <h1>Célestin Bouchet</h1>
            <p>Responsable backend</p>
        </article>
        <article>
            <img src="../View/design/img/Clement.jpg" alt="Clément">
            <h1>Clément Mazet</h1>
            <p>Responsable tests</p>
        </article>
        <article>
            <img src="../View/design/img/Gatien.jpg" alt="Gatien">
            <h1>Gatien Caillet</h1>
            <p>Responsable frontend</p>
        </article>
        <article>
            <img src="../View/design/img/Hippolyte.png" alt="Hippolyte">
            <h1>Hippolyte Chauvin</h1>
            <p>Responsable serveur</p>
        </article>
        <article>
            <img src="../View/design/img/Paul.png" alt="Paul">
            <h1>Paul Sode</h1>
            <p>Chef de projet</p>
        </article>
    </main>
    <footer class="absolute">
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
    </footer>
</body>