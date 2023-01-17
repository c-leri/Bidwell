<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Site de vente aux enchères de particulier à particulier">
    <meta name="keywords"
        content="Bidwell, Bidwell.fr, Vente aux enchères, Vente aux enchères en ligne, Art, Bijouterie, Joaillerie, Mobilier, Mode, Bijoux, Sculptures, Monnaies, Pierres, Objets de collection">
    <meta name="author"
        content="Paul Sode, Gatien Caillet, Célestin Bouchet, Antoine Vuillet, Clément Mazet, Hippolyte Chauvin">
    <title>Connexion</title>
    <link rel="stylesheet" href="../View/design/styleGeneral.css">
    <link rel="stylesheet" href="../View/design/styleConnect.css">
    <link rel="stylesheet" href="../View/design/styleMenu.css">
    <link rel="icon" type="image/x-icon" href="../View/design/img/favicon.ico">
    <link rel="stylesheet" href="../View/design/styleFooter2.css">

</head>



<body>
    <!-- Menu -->
    <header>
        <?php include(__DIR__ . '/menu.viewpart.php') ?>
    </header>
    <main class="connect">
        <div class="left">
            <h2> Se connecter </h2>

            <form id="connect-form" accept-charset="utf-8" action="" method="post" onsubmit="return validateConnection(event)">
                <h3>Nom d'utilisateur</h3>
                <input id="login" type="text" dir="auto" spellcheck="false" autocomplete="false" placeholder="Nom d'utilisateur" name="login" value="<?=$_SESSION['login'] ?? ''?>">
                <p id="errorlogin"></p>
                <a href="">Nom d'utilisateur oublié ?</a>


                <h3>Mot de passe</h3>
                <input id="password" type="password" dir="auto" spellcheck="false" autocomplete="false" placeholder="Mot de passe" name="password">
                <p id="errorpassword"></p>
                <a href="">Mot de passe oublié ?</a>
                <button type="submit">Se connecter</button>
            </form>
            <script src="../JS/connectChecker.js"></script>

        </div>
        

        <div class="center">
            <div id="top">
                <h2> Inscription </h2>

                <form id="signup-form" accept-charset="utf-8" action="signup.ctrl.php" method="get">
                    <button type="submit">Inscription</button>
                </form>
            </div>
            <hr>
            <div id="bot">
                <h2> Liens utiles </h2>
                <ul>
                <li><a href="condition.ctrl.php">Conditions d’utilisation</a></li>
                <li><a href="cookie.ctrl.php">Cookies</a></li>
                <li><a href="#">Mentions légales</a></li>
                <li><a href="#">Politique de confidentalité</a></li>
                </ul>
            </div>
        </div>
        <div class="right">
        <h2> FAQ </h2>
        <h3>Comment me créer un compte ?</h3>
        <p>Il suffit de cliquer sur le bouton "inscription" en haut à droite de la page.</p>
        <h3></h3>
        </div>
    </main>
    <footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
    </footer>
</body>



</html>