<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Site de vente aux enchères de particulier à particulier">
    <meta name="keywords"
        content="Bidwell, Bidwell.fr, Vente aux enchères, Vente aux enchères en ligne, Art, Bijouterie, Joaillerie, Mobilier, Mode, Bijoux, Sculptures, Monnaies, Pierres, Objets de collection">
    <meta name="author"
        content="Paul Sode, Gatien Caillet, Célestin Bouchet, Antoine Vuillet, Clément Mazet, Hippolyte Chauvin">
    <title>Mon compte</title>
    <link rel="stylesheet" href="../View/design/styleGeneral.css">
    <link rel="stylesheet" href="../View/design/styleMenu.css">
    <link rel="stylesheet" href="../View/design/styleFooter.css">
    <link rel="stylesheet" href="../View/design/styleCompte.css">
    <link rel="stylesheet" href="../View/design/stylePagePrincipale.css">
    <link rel="icon" type="image/x-icon" href="../View/design/img/favicon.ico">
</head>

<body>
    <!-- Menu -->
    <header>
    <?php include isset($_SESSION['login']) ? __DIR__ . '/menu_connecte.viewpart.php' : __DIR__ . '/menu.viewpart.php'; ?>
    </header>
    <main class="compte">
        <h1 id="login"><?= $login ?></h1>
        <hr />
        <h2>Données personnelles</h2>

        <h2 id="erreur"></h2>

        <ul class="listeCompte">
            <li>Adresse Mail : <?= $email ?></li>
            <li>Téléphone : <?= $numtel ?></li>
            <?php if ($dateFin !== false) : ?>
            <li>Nous conserverons vos informations pendant <?= $dateFin ?></li>
            <?php endif; ?>
            <li>Nous conservons vos données personnelles dans l'unique but de vous authentifier. Afin de supprimer vos informations, veuillez supprimer votre compte.</li>

            <li><button id="lasupp" class="button" type="submit" onclick="affichageConfirmation()"> Supprimer votre compte </button></li>
        </ul>
        <hr />
        <h2>Jetons</h2>

        <ul class="listeCompte">
            <li>Vous avez <?= $nbJetons ?> jetons</li>
            <li>Les jetons sont une monnaie propre à Bidwell qui vous permet d'enchérir plusieurs fois sur une même annonce.</li>
            <li><a class="button" href='shop.ctrl.php'> Acheter des Jetons </a></li>
        </ul>
        <hr />
        <h2 class="margin-bot">Vos Enchères</h2>
        <div class="annoncesCarre" id="vosEncheres">

        </div>

        <hr id="hrWon">

        <h2 class="margin-bot" id="titleWon">Enchères remportées</h2>
        <div class="annoncesCarre" id="won">

        </div>

        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="stop()">&times;</span>
                <p>Êtes-vous sûr de vouloir supprimer définitivement votre compte ? Cette action n'est pas réversible.</p>
                <button class="btnmodal" id="btnconf" onclick="suppressionCompte('<?= $login ?>')">Supprimer définitivement mon compte</button>
            </div>
        </div>

        <div id="myModeul" class="modal">
            <div class="modal-content">
                <span class="close" onclick="stop()">&times;</span>
                <p>Voulez-vous vraiment supprimer cette enchère ?</p>
                <button class="btnmodal" id="btnsuppr" onclick="suppressionEnchere()">Supprimer</button>
            </div>
        </div>
    </main>
    <footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
    </footer>
</body>

<script type="text/javascript" src="../JS/compte.js"></script>