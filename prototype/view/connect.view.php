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
    <title>Connexion</title>
    <link rel="stylesheet" href="design/styleConnect.css">
    <link rel="stylesheet" href="design/styleMenu.css">

</head>

<!-- Menu -->
<header>
    <?php include(__DIR__ . '/menu.viewpart.php') ?>
</header>

<body>
    <main>
        <div class="left">
            <h2> Se connecter </h2>

            <form id="connect-form" accept-charset="utf-8" action="" method="get">
                <h3>Nom d'utilisateur</h3>
                <input type="text" dir="auto" aria-label="Nom" spellcheck="false" autocomplete="false">

                <a href="">Nom d'utilisateur oublié ?</a>


                <h3>Mot de passe</h3>
                <input type="text" dir="auto" aria-label="MDP" spellcheck="false" autocomplete="false">

                <a href="">Mot de passe oublié ?</a>

                <button type="submit">Se connecter</button>
            </form>
        </div>


        <div class="center">
            <div id="top">
                <h2> Inscription </h2>

                <form id="signup-form" accept-charset="utf-8" action="" method="get">
                    <button type="submit">Inscription</button>
                </form>
            </div>

            <div class="bot">
                <h2> Liens utiles </h2>
                <li><a href="#"></a>Conditions d’utilisation</li>
                <li><a href="#"></a>Cookies</li>
                <li><a href="#"></a>Mentions légales</li>
                <li><a href="#"></a>Politique de confidentalité</li>
                <a>
            </div>
        </div>
    </main>

    <aside>
        <h2> FAQ </h2>
        Lorem Ipsum
    </aside>
</body>