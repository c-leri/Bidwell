<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Site de vente aux enchères de particulier à particulier">
    <meta name="keywords"
        content="Bidwell, Bidwell.fr, Vente aux enchères, Vente aux enchères en ligne, Art, Bijouterie, Joaillerie, Mobilier, Mode, Bijoux, Sculptures, Monnaies, Pierres, Objets de collection">
    <meta name="author"
        content="Paul Sode, Gatien Caillet, Célestin Bouchet, Antoine Vuillet, Clément Mazet, Hippolyte Chauvin">
    <title>Connexion</title>
    <link rel="stylesheet" href="../../view/design/styleGeneral.css">
    <link rel="stylesheet" href="../../view/design/styleConnect.css">
    <link rel="stylesheet" href="../../view/design/styleMenu.css">
    <link rel="stylesheet" href="../view/design/footer.css">
    <link rel="icon" type="image/x-icon" href="../../view/design/img/favicon.ico">

</head>

<!-- Menu -->
<header>
<?php 
    if(isset($_SESSION['login'])){
        include(__DIR__ . '/menu_connecte.viewpart.php');
    }else{
        include(__DIR__ . '/menu.viewpart.php');
    }
    ?>
</header>

<body>


<footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
</footer>
</body>
</html>