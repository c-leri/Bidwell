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

        <h1>Achats de jetons</h1>

        <div id="bundles">

            <div class="pack">
                <p>72</p>
            <button class="buy" onclick="affish()">5,99€</button>
            </div>

            <div class="pack">
                <p>134 +10% = 150</p>
            <button class="buy" onclick="affish()">10,99€</button>
            </div>

            <div class="pack">
                <p>250 +20%=300</p>
            <button class="buy" onclick="affish()">20,99€</button>
            </div>
            
            <div class="pack">
                <p>600 +30%= 780</p>
            <button class="buy" onclick="affish()">49,99€</button>
            </div>

            <div class="pack">
                <p>1200+40%=1700</p>
            <button class="buy" onclick="affish()">99,99€</button>
            </div>

        </div>

        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="stop()">&times;</span>
                <p>Some text in the Modal..</p>
                <button id="myConfirmation" onclick="conf()">Confirmer</button>
                <button id="myCancel" onclick="stop()">Annuler</button>
            </div>
            <script src="../js/shop.js"></script>
        </div>
    </main>

    <footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
    </footer>
    

</body>


</html>