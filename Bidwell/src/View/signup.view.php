<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Site de vente aux enchères de particulier à particulier">
    <meta name="keywords" content="Bidwell, Bidwell.fr, Vente aux enchères, Vente aux enchères en ligne, Art, Bijouterie, Joaillerie, Mobilier, Mode, Bijoux, Sculptures, Monnais, Pierres, Objets de collection">
    <meta name="author" content="Paul Sode, Gatien Caillet, Célestin Bouchet, Antoine Vuillet, Clément Mazet, Hippolyte Chauvin">
    <title>Connexion</title>
    <link rel="stylesheet" href="../View/design/styleGeneral.css">
    <link rel="stylesheet" href="../View/design/styleConnect.css">
    <link rel="stylesheet" href="../View/design/styleMenu.css">
    <link rel="stylesheet" href="../View/design/styleCookies.css">
    <link rel="icon" type="image/x-icon" href="../View/design/img/favicon.ico">
    <link rel="stylesheet" href="../View/design/styleFooter.css">
</head>



<body onload="checkCookie('infosPersonnelles')">
    <!-- Menu -->
    <header>
        <?php include(__DIR__ . '/menu.viewpart.php') ?>
    </header>

    <?php include(__DIR__ . '/cookie_popup.viewpart.php'); ?>

    <main class="connect">
        <div class="left">
            <h2> S'inscrire </h2>
            <form id="signin-form" accept-charset="utf-8" action="" method="post" name="register-form" onsubmit="return validateInfos(event)">
                <h3>Nom d'utilisateur</h3>
                <input id="username" name="username" type="text" dir="auto" spellcheck="false" autocomplete="off" placeholder="Nom d'utilisateur" onkeyup="validateLogin()" onchange="validateLogin()">
                <p id="errorusername"></p>
                <h3>Mot de passe</h3>
                <input id="password" name="password" type="password" dir="auto" spellcheck="false" autocomplete="off" placeholder="Mot de passe" onkeyup="validatePassword()" onchange="validatePassword()">
                <h3>Confirmer le mot de passe</h3>
                <input id="confirm_password" type="password" dir="auto" spellcheck="false" autocomplete="off"  placeholder="Confirmer le mot de passe" onkeyup="validateConfirmPassword()" onchange="validateConfirmPassword()">
                <p id="errorpassword"></p>

                <h3>Mail</h3>
                <input id="mail" name="email" type="text" dir="auto" spellcheck="false" autocomplete="off" placeholder="Adresse mail" onkeyup="validateEmail()" onchange="validateEmail()">
                <p id="erroremail"></p>
                <h3>Téléphone</h3>
                <input id="tel" name="phone" type="text" dir="auto" spellcheck="false" autocomplete="off" placeholder="Numéro de téléphone" onkeyup="validatePhoneNumber()" onchange="validatePhoneNumber()">
                <p id="errornumtel"></p>
                <button type="submit" name="submit">Confirmer</button>
            </form>
            <script src="../JS/signup.js"></script>
        </div>


        <div class="center">
            <div id="top">
                <h2> Vous avez déjà un compte ? </h2>

                <form id="connect-form" accept-charset="utf-8" action="connect.ctrl.php" method="get">
                    <button type="submit">Connexion</button>
                </form>
            </div>
            <hr>
            <div id="bot">
                <h2> Liens utiles </h2>
                <ul>
                <li><a href="condition.ctrl.php">Conditions d’utilisation</a></li>
                <li><a href="mention.ctrl.php">Mentions légales</a></li>
                </ul>
            </div>
        </div>
        <div class="right">
            <h2> FAQ </h2>
            <h3>Comment me connecter si j'ai déjà un compte ?</h3>
            <p>Le bouton "connexion" vous permet de vous connecter à tout moment une fois que vous avez créer un compte.
            </p>
            <h3>Que vendez-vous sur ce site ?</h3>
            <p>La vente ce fait entre particuliers cependant les objets qui sont vendus doivent être de collection.
            </p>
            <h3>Comment vendre un objet ?</h3>
            <p> Il faut être connecté pour pouvoir vendre un objet de collection.
            </p>
        </div>
    </main>
    <footer class="absolute">
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
    </footer>
</body>


</html>

<script type="text/javascript" src="../JS/cookie.js"></script>