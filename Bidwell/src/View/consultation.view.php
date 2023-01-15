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
        <?php include(__DIR__ . '/menu.viewpart.php') ?>
    </header>
    
    <main class="consultation">
        <div class="top">
            <div class="presentation">
                <img src="../View/design/img/default_image.png" alt="mainimage">

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
                <?php for ($i = 0; $i < 7; $i++) {
                    ?>
                    <img src="../View/design/img/default_image.png" alt="logo">
                <?php } ?>
            </div>
        </div>

        <div class="complement">
            <div class="informations">
                <h2> Informations complémentaires </h2>
                <ul>
                    <li>Livraison</li>
                    <li>Paiement</li>
                    <li>Conditions particulières</li>
                </ul>
            </div>

            <div class="vendeur">
                <h2> Vendeur </h2>
                <ul>
                    <li>Nom</li>
                    <li>Contact (Tel)</li>
                    <li>Contact (Mél)</li>
                    <li>Nombre d'annonces postées</li>
                </ul>
            </div>
        </div>
    </main>
    <footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
    </footer>

    <body>


    <script>
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
});</script>

</html>