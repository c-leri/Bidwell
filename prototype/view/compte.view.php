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
    <link rel="stylesheet" href="../view/design/styleGeneral.css">
    <link rel="stylesheet" href="../view/design/styleMenu.css">
    <link rel="stylesheet" href="../view/design/styleFooter2.css">
    <link rel="stylesheet" href="../view/design/styleCompte.css">
    <link rel="icon" type="image/x-icon" href="../view/design/img/favicon.ico">

</head>



<body>
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
    <main class="compte">
        <h2>Compte de nom</h2>
        <hr>
        <ul>
            <li>Connecté en tant que : login </li>
            <li>Connecté avec l'adresse : email </li>
            <li>Numéro de téléphone associé : numeroTelephone </li>
            <li> Jetons : nbJetons </li>
            <li> Nous conservons vos informations jusqu'au : dateFinConservation </li>
            <li> <button type="submit"> Supprimer compte </button> </li>
        </ul>
    </main>
    <footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
    </footer>
</body>