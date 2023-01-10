<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Site de vente aux enchères de particulier à particulier">
    <meta name="keywords"
        content="Bidwell, Bidwell.fr, Vente aux enchères, Vente aux enchères en ligne, Art, Bijouterie, Joaillerie, Mobilier, Mode, Bijoux, Sculptures, Monnais, Pierres, Objets de collection">
    <meta name="author"
        content="Paul Sode, Gatien Caillet, Célestin Bouchet, Antoine Vuillet, Clément Mazet, Hippolyte Chauvin">
    <title>Connexion</title>
    <link rel="stylesheet" href="../view/design/styleGeneral.css">
    <link rel="stylesheet" href="../view/design/styleConnect.css">
    <link rel="stylesheet" href="../view/design/styleMenu.css">
    <link rel="icon" type="image/x-icon" href="../view/design/img/favicon.ico">
    <link rel="stylesheet" href="../view/design/styleFooter2.css">
</head>



<body>
    <!-- Menu -->
    <header>
        <?php include(__DIR__ . '/menu.viewpart.php') ?>
    </header>
    <main class="connect">
        <div class="left">
            <h2> S'inscrire </h2>
            <form id="signin-form" accept-charset="utf-8" action="" method="post" name="register-form" >
                <h3>Nom d'utilisateur</h3>
                <input type="text" dir="auto" spellcheck="false" autocomplete="off" required minlength="4" maxlength="16" >
                <p class="error username-error"></p>

                <h3>Mot de passe</h3>
                <input id="password" type="password" dir="auto" spellcheck="false" autocomplete="off"  required minlength="8" maxlength="16">
                <p class="error password-error"></p>

                <h3>Confirmer le mot de passe</h3>
                <input id="confirm_password" type="password" dir="auto" spellcheck="false" autocomplete="off" required minlength="8" maxlength="16">
                <p class="error confirm-password-error"></p>

                <h3>Mail</h3>
                <input id="mail" type="email" dir="auto" spellcheck="false" autocomplete="off" required maxlength="32">
                <p class="error mail-error"></p>

                <h3>Téléphone</h3>
                <input id="tel" type="tel" dir="auto" spellcheck="false" autocomplete="off" required pattern="[0-9]{10}">
                <p class="error phone-error"></p>

                <button type="submit" name="submit">Confirmer</button>
            </form>
            <script src="../js/form_checker.js"></script>
        </div>


        <div class="center">
            <div id="top">
                <h2> Vous avez déjà un compte ? </h2>

                <form id="connect-form" accept-charset="utf-8" action="connect.ctrl.php" method="get">
                    <button type="submit">Connexion</button>
                </form>
            </div>

            <div id="bot">
                <h2> Liens utiles </h2>
                <ul>
                <li><a href="#">Conditions d’utilisation</a></li>
                <li><a href="#">Cookies</a></li>
                <li><a href="#">Mentions légales</a></li>
                <li><a href="#">Politique de confidentalité</a></li>
                </ul>
            </div>
        </div>
        <div class="right">
        <h2> FAQ </h2>
        <p>truc style de Paul</p>
        </div>
    </main>
    <footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
    </footer>
</body>


</html>