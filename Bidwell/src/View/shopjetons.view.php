<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Site de vente aux enchères de particulier à particulier">
    <meta name="keywords"
        content="Bidwell, Bidwell.fr, Vente aux enchères, Vente aux enchères en ligne, Art, Bijouterie, Joaillerie, Mobilier, Mode, Bijoux, Sculptures, Monnaies, Pierres, Objets de collection">
    <meta name="author"
        content="Paul Sode, Gatien Caillet, Célestin Bouchet, Antoine Vuillet, Clément Mazet, Hippolyte Chauvin">
    <title>Shop</title>
    <link rel="stylesheet" href="../view/design/styleGeneral.css">
    <link rel="stylesheet" href="../view/design/styleMenu.css">
    <link rel="stylesheet" href="../view/design/styleSous-Menu_Categorie.css">
    <link rel="stylesheet" href="../view/design/styleFooter.css">
    <link rel="stylesheet" href="../view/design/styleShop.css">

</head>


<body>
    <!-- Menu -->
    <header>
        <?php
        include(__DIR__ . '/menu_connecte.viewpart.php');
        include(__DIR__ . '/sous-menu_categorie.viewpart.php')
        ?>
    </header>
    <main class="Prix">

        <div class="possede">
            <p> Vous avez actuellement </p>
            <p> X jetons</p>
            <p> Les jetons vous permettent d'enchérir plus d'une fois sur une même enchère. Acheter des jetons supplémentaires vous permet d'augmenter vos chances de remporter une enchère. </p>
</div>

        <div class="achat">
        <h2>Acheter des jetons</h2>

        <div id="bundles">

            <div class="pack">
                <p>5,99€</p>
            <button class="buy" onclick="affish()">72 jetons</button>
            </div>

            <div class="pack">
                <p>10,99€</p>
            <button class="buy" onclick="affish()">134 jetons</button>
                <p>+10% bonus</p>
            </div>

            <div class="pack">
                <p>20,99€</p>
            <button class="buy" onclick="affish()">250 jetons</button>
            <p>+20% bonus</p>
            </div>
            
            <div class="pack">
                <p>49,99€</p>
            <button class="buy" onclick="affish()">600 jetons</button>
            <p>+30% bonus</p>
            </div>

            <div class="pack">
                <p>99,99€</p>
            <button class="buy" onclick="affish()">1200 jetons</button>
            <p>+40% bonus</p>
            </div>

            <p class="petit"> En achetant des jetons, vous obtenez une licence limitée, non remboursable, non transférable et révocable pour utiliser ces jetons.qui n'ont aucune valeur en monnaie réelle. </p>

        </div>
</div>

        <form id="myModal" class="modal" action="shopAchat.ctrl.php" method="post">
            <div class="modal-content">
                <span class="close" onclick="stop()">&times;</span>
                <p>Some text in the Modal..</p>
                <button type="submit" id="myConfirmation" name="valeur" value="" onclick="conf()">Confirmer</button>
                <button id="myCancel" onclick="stop()">Annuler</button>
            </div>
            <script src="../js/shop.js"></script>
        </form>
    </main>

    <footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
    </footer>
    

</body>


</html>