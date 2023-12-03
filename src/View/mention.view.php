<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Site de vente aux enchères de particulier à particulier">
    <meta name="keywords"
        content="Bidwell, Bidwell.fr, Vente aux enchères, Vente aux enchères en ligne, Art, Bijouterie, Joaillerie, Mobilier, Mode, Bijoux, Sculptures, Monnaies, Pierres, Objets de collection">
    <meta name="author"
        content="Paul Sode, Gatien Caillet, Célestin Bouchet, Antoine Vuillet, Clément Mazet, Hippolyte Chauvin">
    <title>Mentions légales</title>
    <link rel="stylesheet" href="../View/design/styleGeneral.css">
    <link rel="stylesheet" href="../View/design/styleMenu.css">
    <link rel="stylesheet" href="../View/design/styleFooter.css">
    <link rel="stylesheet" href="../View/design/styleMention.css">
    <link rel="icon" type="image/x-icon" href="../View/design/img/favicon.ico">

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
<main class="mention">
<h1>Mentions légales</h1>
<h3>En vigueur au 18/01/2023</h3>
    <p>Conformément aux dispositions des Articles 6-III et 19 de la Loi n°2004-575 du 21 juin 2004 pour la
    Confiance dans l'économie numérique, dite L.C.E.N., il est porté à la connaissance des utilisateurs et
    visiteurs, ci-après l'Utilisateur, du site http://192.168.14.212 , ci-après le "Site", les présentes
    mentions légales.</p>

    <p>La connexion et la navigation sur le Site par l'Utilisateur implique acceptation intégrale et sans réserve
    des présentes mentions légales.</p>

    <p>Ces dernières sont accessibles sur le Site à la ruhrique « Mentions légales ».</p>
    <hr>
<h2>ARTICLE 1 - L'EDITEUR</h2>
    <p>L'édition et la direction de la publication du Site est assurée par Clément Mazet, domicilié 15 rue
    arago, dont le numéro de téléphone est 0782256941, et l'adresse e-mail clement.mazett@gmail.com<p>
    <hr>
<h2>ARTICLE 2 - L'HEBERGEUR</h2>
    <p>L'hébergeur du Site est la société iut2, dont le siège social est situé au SARL , avec le numéro de
    téléphone : 21153314121 + adresse mail de contact<p>
    <hr>
<h2>ARTICLE 3 - ACCES AU SITE</h2>
    <p>Le Site est accessible en tout endroit, 7j/7, 24h/24 sauf cas de force majeure, interruption
    programmée ou non et pouvant découlant d'une nécessité de maintenance.
    En cas de modification, interruption ou suspension du Site, l'Editeur ne saurait être tenu responsable.<p>
    <hr>
<h2>ARTICLE 4 - COLLECTE DES DONNEES</h2>
    <p>Le Site assure à l'Utilisateur une collecte et un traitement d'informations personnelles dans le respect
    de la vie privée conformément à la loi n°78-17 du 6 janvier 1978 relative à l'informatique, aux fichiers
    et aux libertés.</p>

    <p>En vertu de la loi Informatique et Libertés, en date du 6 janvier 1978, l'Utilisateur dispose d'un droit
    d'accès, de rectification, de suppression et d'opposition de ses données personnelles. L'Utilisateur
    exerce ce droit :
    · via un formulaire de contact </p>

    <p>Toute utilisation, reproduction, diffusion, commercialisation, modification de toute ou partie du Site,
sans autorisation de l'Editeur est prohibée et pourra entraînée des actions et poursuites judiciaires
telles que notamment prévues par le Code de la propriété intellectuelle et le Code civil.</p>
</main>
<footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
</footer>
</body>
</html>