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
    <link rel="stylesheet" href="../View/design/styleGeneral.css">
    <link rel="stylesheet" href="../View/design/styleMenu.css">
    <link rel="stylesheet" href="../View/design/styleSous-Menu_Categorie.css">
    <link rel="stylesheet" href="../View/design/styleFooter.css">
    <link rel="stylesheet" href="../View/design/styleShop.css">
    <link rel="icon" type="image/x-icon" href="../View/design/img/favicon.ico">

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
    <h2>Acheter des jetons</h2>
        <div class="possede">
            <p> Vous avez actuellement </p>
            <p id="nombrejetons"> <?=$jet?> jetons</p>
            <p> Les jetons vous permettent d'enchérir plus d'une fois sur une même enchère. Acheter des jetons supplémentaires vous permet d'augmenter vos chances de remporter une enchère. </p>
        </div>

        <div id="bundles">

            <div class="pack">
                <div class="imagetdose">
                    <img src="../View/design/img/BidCoin.png" alt="BidCoin"></span>
                    <p>72 Jetons</p>
                </div>
                <button class="buybtn" onclick="affish(72,5.99)">5,99€</button>
            </div>

            <div class="pack">
                <div class="imagetdose">
                    <img src="../View/design/img/BidCoin.png" alt="BidCoin"></span>
                    <p>134 +10% = 150 Jetons</p>
                </div>
                <button class="buybtn" onclick="affish(150,10.99)">10,99€</button>
            </div>

            <div class="pack">
                <div class="imagetdose">
                    <img src="../View/design/img/BidCoin.png" alt="BidCoin"></span>
                    <p>250 +20%=300 Jetons</p>
                </div>
                <button class="buybtn" onclick="affish(300,20.99)">20,99€</button>
            </div>
            
            <div class="pack">
                <div class="imagetdose">
                    <img src="../View/design/img/BidCoin.png" alt="BidCoin"></span>
                    <p>600 +30%= 780 Jetons</p>
                </div>
                <button class="buybtn" onclick="affish(780,49.99)">49,99€</button>
            </div>

            <div class="pack">
                <div class="imagetdose">
                    <img src="../View/design/img/BidCoin.png" alt="BidCoin"></span>
                    <p>1200+40%=1700 Jetons</p>
                </div>
                <button class="buybtn" onclick="affish(1700,99.99)">99,99€</button>
            </div>

            <div class="pack">
                <div class="imagetdose">
                    <img src="../View/design/img/BidCoin.png" alt="BidCoin"></span>
                    <p>admin</p>
                </div>
                <button class="buybtn" onclick="affish(9999,0.99)">0.99€</button>
            </div>

            <p class="petit"> En achetant des jetons, vous obtenez une licence limitée, non remboursable, non transférable et révocable pour utiliser ces jetons.qui n'ont aucune valeur en monnaie réelle. </p>

        </div>

        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="stop()">&times;</span>
                <p id='letext'>Some text in the Modal..</p>
                <div id="lesbtns">
                <button id="myConfirm" onclick="conf()">Confirmer</button>
                <button id="myCancel" onclick="stop()">Annuler</button>
                    <div id="smart-button-container">
                        <div style="text-align: center;">
                            <div id="paypal-button-container"></div>
                        </div>
                    </div>
                    <script src="https://www.paypal.com/sdk/js?client-id=sb&enable-funding=venmo&currency=EUR" data-sdk-integration-source="button-factory"></script>
                </div>
            </div>
            <script src="../JS/shop.js"></script>
        </div>
    </main>

    <footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
    </footer>
    

</body>


</html>