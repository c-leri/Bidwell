<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Site de vente aux enchères de particulier à particulier">
    <meta name="keywords"
          content="Bidwell, Bidwell.fr, Vente aux enchères, Vente aux enchères en ligne, Art, Bijouterie, Joaillerie, Mobilier, Mode, Bijoux, Sculptures, Monnaies, Pierres, Objets de collection">
    <meta name="author"
          content="Paul Sode, Gatien Caillet, Célestin Bouchet, Antoine Vuillet, Clément Mazet, Hippolyte Chauvin">
    <title>Besoin d'aide ?</title>
    <link rel="stylesheet" href="../View/design/styleGeneral.css">
    <link rel="stylesheet" href="../View/design/styleMenu.css">
    <link rel="stylesheet" href="../View/design/styleFooter.css">
    <link rel="stylesheet" href="../View/design/styleTutorial.css">
    <link rel="icon" type="image/x-icon" href="../View/design/img/favicon.ico">
</head>

<body>
<!-- Menu -->
<header>
    <?php include isset($_SESSION['login']) ? __DIR__ . '/menu_connecte.viewpart.php' : __DIR__ . '/menu.viewpart.php'; ?>
</header>
<main class="tutorial">
    <h2>Comment fonctionne le système d'enchère ?</h2>
    <p>Notre système est basé sur un principe d'enchère descendante, le prix descend de son prix de départ à son prix de retrait.</p>
    
</main>
<footer>
    <?php include(__DIR__ . '/footer.viewpart.php') ?>
</footer>
</body>
