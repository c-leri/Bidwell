<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Site de vente aux enchères de particulier à particulier">
    <meta name="keywords"
        content="Bidwell, Bidwell.fr, Vente aux enchères, Vente aux enchères en ligne, Art, Bijouterie, Joaillerie, Mobilier, Mode, Bijoux, Sculptures, Monnaies, Pierres, Objets de collection">
    <meta name="author"
        content="Paul Sode, Gatien Caillet, Célestin Bouchet, Antoine Vuillet, Clément Mazet, Hippolyte Chauvin">
    <title>Conditions d'utilisation</title>
    <link rel="stylesheet" href="../View/design/styleGeneral.css">
    <link rel="stylesheet" href="../View/design/styleMenu.css">
    <link rel="stylesheet" href="../View/design/styleFooter.css">
    <link rel="stylesheet" href="../View/design/styleCompte.css">
    <link rel="icon" type="image/x-icon" href="../View/design/img/favicon.ico">

</head>



<body>
    <!-- Menu -->
    <header>
    <?php include isset($_SESSION['login']) ? __DIR__ . '/menu_connecte.viewpart.php' : __DIR__ . '/menu.viewpart.php'; ?>
    </header>
    <main>
        <h1>Compte de <?= $login ?></h1>
        <hr />
        <h2>Données personnelles</h2>
        <ul>
            <li>Connecté avec l'adresse : <?= $email ?></li>
            <li>Numéro de téléphone associé : <?= $numtel ?></li>
            <li>Nous conservons vos informations pendant encore : <?= $dateFin ?></li>
            <li>Nous conservons vos données personnelles dans l'unique but de vous authentifier, si vous souhaitez que nous arrétions de le conserver vous devrez supprimer votre compte.</li>
            <li><button class="button" type="submit"> Supprimer votre compte </button></li>
        </ul>
        <hr />
        <h2>Jetons</h2>
        <ul>
            <li>Les jetons sont une monnaies propre à Bidwell qui vous permet d'enchérir plusieurs fois sur une même annonce.</li>
            <li><a class="button" href='shop.ctrl.php'> Acheter des Jetons </a></li>
        </ul>
        <hr />
        <h2>Vos Enchères</h2>
        <div class="vosEncheres">
        </div>
    </main>
    <footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
    </footer>
</body>