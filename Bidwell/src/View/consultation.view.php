<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Site de vente aux enchères de particulier à particulier">
    <meta name="keywords"
        content="Bidwell, Bidwell.fr, Vente aux enchères, Vente aux enchères en ligne, Art, Bijouterie, Joaillerie, Mobilier, Mode, Bijoux, Sculptures, Monnaies, Pierres, Objets de collection">
    <meta name="author" content="Paul Sode, Gatien Caillet, Célestin Bouchet, Antoine Vuillet, Clément Mazet, Hippolyte Chauvin">
    <title>Consultation d'un article</title>
    <link rel="stylesheet" href="../View/design/styleGeneral.css">
    <link rel="stylesheet" href="../View/design/styleMenu.css">
    <link rel="stylesheet" href="../View/design/styleFooter.css">
    <link rel="stylesheet" href="../View/design/styleConsultation.css">
    <link rel="stylesheet" href="../View/design/styleEnchere.css">
    <link rel="icon" type="image/x-icon" href="../View/design/img/favicon.ico">

</head>

<body>
    <!-- Menu -->
    <header>
        <?php include ($login === '') ? __DIR__ . '/menu.viewpart.php' : __DIR__.  '/menu_connecte.viewpart.php' ?>
    </header>
    
    <main class="consultation">
        <div class="top">
            <div class="presentation">
                <img src=<?= $images[0]?> alt="mainimage">

                <p> <?= $nom ?> </p>
            </div>

            <div class="enchere">

                <?php include(__DIR__ . '/enchere.viewpart.php') ?>

            </div>
        </div>

        <div class="descr">
            <h2>Description</h2>

            <p class="description"> <?= $description ?> </p>

            <div class="images">
                <?php for ($i = 1; $i < sizeof($images); $i++) {
                    ?>
                    <img src=<?= $images[$i] ?> alt="logo">
                <?php } ?>
            </div>
        </div>

        <div class="complement">
            <div class="informations">
                <h2> Informations complémentaires </h2>
                <ul>
                    <li> <?= $place?> </li>
                    <li> <?= $dist?> </li>
                    <li>Le paiement sera réalisé par PayPal</li>
                </ul>
            </div>

            <div class="vendeur">
                <h2> Vendeur </h2>
                <ul>
                    <li><?= $createur->getNom() ?></li>
                    <li><?= $mail ?></li>
                    <li><?= $tel ?></li>
                    <!--<li>Nombre d'annonces postées</li>-->
                </ul>
            </div>
        </div>
    </main>
    <footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
    </footer>

    <input type="hidden" id="login" style="display: none;" value="<?= $login ?>" />

    <body>


    <script type="text/javascript">
        document.forms.form01.range.addEventListener('change', e => {
        let numlines = parseInt(e.target.value);
        let numdots = (numlines < 1) ? 0 : numlines+1;
        document.querySelector('#styles').innerHTML = `
            .lines use:nth-child(-n+${numlines}) {
                stroke: DarkSlateBlue;
            }
            .dots use:nth-child(-n+${numdots}) {
                stroke: DarkSlateBlue;
            }`;  
        });
    </script>
    <script type="text/javascript" src="../JS/websocket.js"></script>

</html>