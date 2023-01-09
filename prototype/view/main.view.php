<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Site de vente aux enchères de particulier à particulier">
    <meta name="keywords"
        content="Bidwell, Bidwell.fr, Vente aux enchères, Vente aux enchères en ligne, Art, Bijouterie, Joaillerie, Mobilier, Mode, Bijoux, Sculptures, Monnais, Pierres, Objets de collection">
    <meta name="author"
        content="Paul Sode, Gatien Caillet, Célestin Bouchet, Antoine Vuillet, Clément Mazet, Hippolyte Chauvin">
    <title>Main</title>
    <link rel="stylesheet" href="../view/design/styleGeneral.css">
    <link rel="stylesheet" href="../view/design/styleMenu.css">
    <link rel="stylesheet" href="../view/design/styleSous-Menu_Categorie.css">
    <link rel="stylesheet" href="../view/design/styleFooter.css">
    <link rel="stylesheet" href="../view/design/stylePagePrincipale.css">

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
        include(__DIR__ . '/sous-menu_categorie.viewpart.php') 
        ?>
    </header>
    <main>
        <div id="slider">
            <a href="#"><img src="../view/design/img/logo.png" alt="Besoin d'aide" id="slide"></a>
            <div id="precedent" onclick="ChangeSlide(-1)">
                <</div>
                    <div id="suivant" onclick="ChangeSlide(1)">></div>
            </div>

            <hr>
            <script src="../js/slider.js"></script>
        </div>
            <h2>Populaire en ce moment</h2>
            <div class="ventesPopulaires">
                <?php for ($i = 0; $i < 12; $i++) {
                    ?>
                    <article>
                        <img src="../view/design/img/logo.png" alt="">
                        <h1>Titre------------------------------------------------------</h1>
                        <div class="variablesEnchere">
                            <p class="temps-restant">temps restant</p>
                            <p class="prix-actuel">prix actuel</p>
                        </div>
                    </article>
                <?php } ?>
            </div>
    </main>
    <footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
    </footer>
</body>


</html>
