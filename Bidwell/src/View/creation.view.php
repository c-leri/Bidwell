<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Site de vente aux enchères de particulier à particulier">
    <meta name="keywords" content="Bidwell, Bidwell.fr, Vente aux enchères, Vente aux enchères en ligne, Art, Bijouterie, Joaillerie, Mobilier, Mode, Bijoux, Sculptures, Monnais, Pierres, Objets de collection">
    <meta name="author" content="Paul Sode, Gatien Caillet, Célestin Bouchet, Antoine Vuillet, Clément Mazet, Hippolyte Chauvin">
    <title>Vendre un article</title>
    <link rel="stylesheet" href="../View/design/styleGeneral.css">
    <link rel="stylesheet" href="../View/design/styleMenu.css">
    <link rel="stylesheet" href="../View/design/styleFooter.css">
    <link rel="icon" type="image/x-icon" href="../View/design/img/favicon.ico">
    <link rel="stylesheet" href="../View/design/styleCreation.css">
</head>

<body>
    <!-- Menu -->
    <header>
        <nav id="navbar-top">
            <div class="nav-left">
                <a href="main.ctrl.php">
                    <img src="../View/design/img/logo.png" alt="logo">
                </a>
            </div>

            <div id="nav-right-cancel">
                <a href="main.ctrl.php">
                    Annuler
                </a>
            </div>

        </nav>
    </header>
    <main class="creation">
        <form action="main.ctrl.php" onsubmit="return validateInfos(event)" method="get">
            <div class="nom">
                <h2> Nom de l'annonce </h2>

                <input id="nom" type="text" required minlength="4" maxlength="60" placeholder="Choisissez un nom">

                <p> Le nom de l'annonce est l'information principale que rechercheront les autres utilisateurs. Donner un
                    nom simple et explicite augmente grandement les chances de vendre un article. </p>
            </div>


            <div class="categorie">
                <h2> Catégorie de l'annonce </h2>
                    <select id="categorieSelect" required>
                        <option id="optionanchor"></option>
                    </select>
                    <p id="errorcategorie"></p>
                    <p> Ajouter une catégorie appropriée à votre annonce lui permettra d'être plus facilement trouvée par
                        les utilisateurs qui pourraient être intéressés. </p>
            </div>

            <div class="description">
                <h2> Description de l'annonce </h2>
                <textarea id="description" name="descr" required cols="50" rows="4" placeholder="Insérez une description (minimum 50 caractères) "></textarea>
                <p id="errordescription"></p>
                <p> Une description complète et détailée de votre bien sera perçue comme de plus grande qualité par les
                    autres utilisateurs. Donnez envie d'acheter votre article et mettez en avant ses informations
                    importantes.</p>
            </div>


            <div class="images">
                <h2> Ajouter des images </h2>

                <input id="imagesInput" type="file" accept="image/*" onchange="loadFile(event)" multiple enctype="multipart/form-data">

                <div class="addedImages">
                    <?php for ($i = 0; $i < 8; $i++) {
                    ?>
                        <article>
                            <img id=<?= "output" . $i ?> src="../View/design/img/default_image.png">
                            <button class="btnSuppr" id=<?= "button" . $i ?> type="button" onclick=removeImg(<?=$i?>)><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"></path><path d="M9 10h2v8H9zm4 0h2v8h-2z"></path></svg></button>
                            <p class="numImg" id=<?= "p" . $i ?>> Image n°<?= $i+1?> </p> 

                        </article>

                    <?php } ?>
                    <p id="errorimgs"></p>
                </div>

                <p> Ajouter plusieurs images à votre annonce permet d'augmenter la confiance des autres utilisateurs
                    envers vous et votre bien. Le plus d'image, le mieux. <br>
                    Triez vos images par ordre d'importance, la première image sera celle affichée dans la liste des
                    annonces</p>
            </div>
            <div class="base">
            <h2> Prix de base </h2>
                <input id="prixbase" type="number" name="base" required min="1" max="99999" placeholder="Prix espéré">

                <p> Définissez le prix auquel vous souhaiteriez vendre votre article. Prenez en compte la valeur réelle
                    de votre article et pensez à ce que vous seriez prêt à mettre à la place de l'acheteur.</p>
            </div>

         

            <div class="retrait">
                <h2> Prix de retrait de l'annonce </h2>

                <input id="prixretrait" type="number" name="retrait" required min="1" max="99999" placeholder="Prix de retrait">
                <p id="errorretrait"></p>
                <p> Notre site utilise en système d'enchère qui comprend une partie descendantes. De ce fait, le prix de
                    votre article pourrait diminuer par rapport au prix de base. Définissez le prix auquel vous ne
                    seriez plus prêt à vendre votre article.</p>
            </div>


            <hr>


            <div class="envoi">
                <h2> Informations d'envoi </h2>
                <ul>
                    <li> Je suis prêt à remettre cet article en main propre
                        <input id="cbdirect" type="checkbox" name="retraitDirect">
                    </li>


                    <li> Je suis prêt à envoyer ce colis vers d'autres villes de France
                        <input id="cbcolis" type="checkbox" name="retraitColis">
                    </li>
                    <p id="errorcbenvoie"></p>

                    <li> Définissez vos méthodes de remise de votre bien. Accepter d'envoyer votre bien vers d'autre ville
                        peut grandement augmenter le nombre d'acheteurs potentiels, mais nécessite une organisation plus
                        complexe.</li>
                </ul>
            </div>


            <div class="localisation">
                <h2> Localisation</h2>
                <input id="localisationInput" type="text" placeholder="Entrez un code postal ou une ville" list="localisationDatalist" onkeyup="filter()" required>
                 <datalist id="localisationDatalist">
                        <option id="optionanchorlocalisation"></option>
                 </datalist>
                 <p id="errorlocalisation"></p>
                <p> Renseigner votre commune permet aux utilisateurs de savoir si la remise en main propre
                    est une option possible par rapport à leur emplacement. <br>
                    Votre adresse exacte ne sera pas connue.</p>
            </div>


            <div class="contact">
                <h2> Contact avec les acheteurs </h2>
                <ul>
                    <li> J'accepte que mon e-mail soit affiché sur la page de mon annonce
                        <input id="cbemail" type="checkbox" name="okEmail">
                    </li>


                    <li> J'accepte que mon numéro de téléphone soit affiché sur la page de mon annonce
                        <input id="cbtel" type="checkbox" name="okTel">
                    </li>
                    <p id="errorcbcontact"></p>

                    <li> Permettre aux autres utilisateurs de vous contacter en cas de question sur votre annonce permet
                        d'augmenter leur confiance envers vous et votre bien.
                        Ces informations ne sont pas nécessaires au bon déroulement de l'enchère.</li>
                </ul>
            </div>

            <button type="submit">Créer annonce</button>
        </form>
        <script src="../JS/creation.js"></script>

    </main>

    <footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
    </footer>

</body>
</html>