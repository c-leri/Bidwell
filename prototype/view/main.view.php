<!DOCTYPE html>
<html>

<head>
    <html lang="fr">
    <meta charset="utf-8">
    <meta name="description" content="Site de vente aux enchères de particulier à particulier">
    <meta name="keywords"
        content="Bidwell, Bidwell.fr, Vente aux enchères, Vente aux enchères en ligne, Art, Bijouterie, Joaillerie, Mobilier, Mode, Bijoux, Sculptures, Monnais, Pierres, Objets de collection">
    <meta name="author"
        content="Paul Sode, Gatien Caillet, Célestin Bouchet, Antoine Vuillet, Clément Mazet, Hippolyte Chauvin">
    <title>Main</title>
    <link rel="stylesheet" href="design/style.css">
</head>
<!-- Menu -->
<header>
<?php include(__DIR__ . '/menu.viewpart.php'); 
 include(__DIR__. '/sous-menu_categorie.viewpart.php') ?>

</header>

<body>
    <div id="slider">
        <a href="#"><img src="../data/img/logo.png" alt="Besoin d'aide" id="slide"></a>
        <div id="precedent" onclick="ChangeSlide(-1)"><</div>
        <div id="suivant" onclick="ChangeSlide(1)">></div>
    </div>

    <hr>
    <script src="../js/slider.js"></script>
</body>

<footer>
    <?php include(__DIR__. '/footer.viewpart.php') ?>
</footer>
</html>