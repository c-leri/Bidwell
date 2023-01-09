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
    <title>Vendre un article</title>
    <link rel="stylesheet" href="../view/design/styleGeneral.css">
    <link rel="stylesheet" href="../view/design/styleMenu.css">
    <link rel="stylesheet" href="../view/design/styleFooter.css">
    <link rel="stylesheet" href="../view/design/styleCreation.css">
    <script src="../js/creation.js"></script>

</head>
<!-- Menu -->
<header>
    <nav id="navbar-top">
        <div class="nav-left">
            <a href="main.ctrl.php">
                <img src="../view/design/img/logo.png" alt="logo">
            </a>
        </div>

        <div id="nav-right-cancel">
            <a href="main.ctrl.php">
                Annuler
            </a>
        </div>

    </nav>
</header>

<body>
    <form action="">
        <div class="nom">
            <h2> Nom de l'annonce </h2>

            <input type="text" name="nom" required minlength="4" maxlength="60" placeholder="Choisissez un nom">

            <p> Le nom de l'annonce est l'information principale que rechercheront les autres utilisateurs. Donner un
                nom simple et explicite augmente grandement les chances de vendre un article. </p>
        </div>


        <div class="categorie">
            <h2> Catégorie de l'annonce </h2>

            <div class="dropdown">
                <input type="text" class="dropbtn" id="categorieInput" name="categorie" placeholder="Choisissez une catégorie"
                    onclick="showFunction()" onkeyup="filterFunction()">
                <div id="categorieDropdown" class="dropdown-content">
                    <button onclick="confirmFunction()">A</button>
                    <button onclick="confirmFunction()">B</button>
                    <button onclick="confirmFunction()">C</button>
                    <button onclick="confirmFunction()">BAC</button>
                    <button onclick="confirmFunction()">ZE</button>
                    <button onclick="confirmFunction()">ABC</button>
                </div>

                <p> Ajouter une catégorie appropriée à votre annonce lui permettra d'être plus facilement trouvée par
                    les utilisateurs qui pourraient être intéressés. </p>
            </div>


            <div class="description">
                <h2> Description de l'annonce </h2>

                <input type="text" name="nom" required minlength="50" maxlength="4000"
                    placeholder="Insérez une description (minimum 50 caractères)">

                <p> Une description complète et détailée de votre bien sera perçue comme de plus grande qualité par les
                    autres utilisateurs. Donnez envie d'acheter votre article et mettez en avant ses informations
                    importantes.</p>
            </div>


            <div class="images">
                <h2> Ajouter des images </h2>

                <input type="file" accept="image/*" onchange="loadFile(event)">

                <div class="addedImages"> <!-- déso pour le nom je savais pas quoi mettre -->
                    <?php for ($i = 1; $i < 9; $i++) {
                        ?>
                        <article>

                            <img id=<?="output" . $i ?>>
                            <p id=<?="p" . $i ?>>Image n°<?= $i ?></p>

                        </article>

                    <?php } ?>

                </div>

                <p> Ajouter plusieurs images à votre annonce permet d'augmenter la confiance des autres utilisateurs
                    envers vous et votre bien. Le plus d'image, le mieux. <br>
                    Triez vos images par ordre d'importance, la première image sera celle affichée dans la liste des
                    annonces</p>
            </div>


            <hr>


            <div class="base">
                <h2> Prix de départ de l'annonce </h2>

                <input type="number" name="base" required min="1" max="99999" placeholder="Prix espéré">

                <p> Définissez le prix auquel vous souhaiteriez vendre votre article. Prenez en compte la valeur réelle
                    de votre article et pensez à ce que vous seriez prêt à mettre à la place de l'acheteur.</p>
            </div>


            <div class="envoi">
                <h2> Informations d'envoi </h2>

                <input type="number" name="retrait" required min="1" max="99999" placeholder="Prix de retrait">

                <p> Notre site utilise en système d'enchère qui comprend une partie descendantes. De ce fait, le prix de
                    votre article pourrait diminuer par rapport au prix de base. Définissez le prix auquel vous ne
                    seriez plus prêt à vendre votre article.</p>
            </div>


            <hr>


            <div class="retrait">
                <h2> Prix de retrait de l'annonce </h2>

                <p> Je suis prêt à remettre cet article en main propre
                <p>
                    <input type="checkbox" name="retraitDirect">

                <p> Je suis prêt à envoyer ce colis vers d'autres villes de France
                <p>
                    <input type="checkbox" name="retraitColis">

                <p> Définissez vos méthodes de remise de votre bien. Accepter d'envoyer votre bien vers d'autre ville
                    peut grandement augmenter le nombre d'acheteurs potentiels, mais nécessite une organisation plus
                    complexe.</p>
            </div>


            <div class="localisation">
                <h2> Localisation du bien (code postal) </h2>

                <input type="number" name="code" required placeholder="Code postal">

                <p> Renseigner le numéro de votre commune permet aux utilisateurs de savoir si la remise en main propre
                    est une option possible par rapport à leur emplacement. <br>
                    Votre adresse exacte ne sera pas connue.</p>
            </div>


            <div class="contact">
                <h2> Prix de retrait de l'annonce </h2>

                <p> J'accepte que mon e-mail soit affiché sur la page de mon annonce
                <p>
                    <input type="checkbox" name="okEmail">

                <p> J'accepte que mon numéro de téléphone soit affiché sur la page de mon annonce
                <p>
                    <input type="checkbox" name="okTel">

                <p> Permettre aux autres utilisateurs de vous contacter en cas de question sur votre annonce permet
                    d'augmenter leur confiance envers vous et votre bien. <br>
                    Ces informations ne sont pas nécessaires au bon déroulement de l'enchère.</p>
            </div>

            <input type="submit" name="submit">
    </form>


    <footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
    </footer>

    <body>

</html>