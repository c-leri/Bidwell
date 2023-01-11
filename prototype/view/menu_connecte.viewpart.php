<!-- Partie de la vue de la création d'enchère : le menu -->
<nav id="navbar-top">
    <div class="nav-left">
        <a href="main.ctrl.php">
            <img src="../view/design/img/logo.png" alt="logo">
        </a>
    </div>

    <div class="nav-search">
        <form id="nav-search-form" method="get" action="recherche.ctrl.php">
            <input type="text" placeholder="Rechercher...">
            <input id="button-submit" type="submit" value="">
        </form>
    </div>

    <div class="nav-center">
        <a href="creation.ctrl.php">
            Vendre un article
        </a>
    </div>
   

    <div class="nav-right">
    <div id="nav-right-logout">
            <a href="disconnect.ctrl.php">
                Déconnexion
                </a>
        </div>
        <div id="nav-right-my_account">
            <a href="">
                Mon compte
            </a>
        </div>
        
    </div>
    
</nav>