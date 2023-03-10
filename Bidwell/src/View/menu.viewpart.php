<!-- Partie d'une vue : le menu -->
<nav id="navbar-top">
    <div class="nav-left">
        <a href="main.ctrl.php">
            <img src="../View/design/img/logo.png" alt="logo">
        </a>
    </div>

    <div class="nav-search">
        <form id="nav-search-form" method="get" action="recherche.ctrl.php">
            <input type="text" placeholder="Rechercher..." name="recherche" >
            <input id="button-submit" type="submit" value="">
        </form>
    </div>

    <div class="nav-center">
        <a href="<?= $connected ? "creation.ctrl.php" : "connect.ctrl.php" ?>">
            Vendre un article
        </a>
    </div>

    <div class="nav-right">
        <div id="nav-right-signin">
                <a href="connect.ctrl.php">
                    Connexion
                </a>
        </div>
        <div id="nav-right-signup">
            <a href="signup.ctrl.php">
                Inscription
            </a>
        </div>
    </div>
</nav>