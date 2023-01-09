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
    <title>Conditions d'utilisation</title>
    <link rel="stylesheet" href="../view/design/styleGeneral.css">
    <link rel="stylesheet" href="../view/design/styleMenu.css">
    <link rel="stylesheet" href="../view/design/styleFooter.css">
    <link rel="stylesheet" href="../view/design/styleConsultation.css">

</head>
<!-- Menu -->
<header>
        <?php include(__DIR__ . '/menu.viewpart.php') ?>
</header>
<body>

<div class="retour">
    <button type="submit">Retour</button>
</div>

<div class="top">
    <div class="presentation">
        <img src="../view/design/img/logo.png" alt="mainimage">

        <p> Nom de l'article </p>
    </div>
    
    <div class="enchere">
        <!-- Aucune idée de comment faire pour l'instant -->
    </div>
</div>

<div class="description">
    <h2>Description</h2>

    <p class="description"> Ceci est un lorem ipsum qui est pas fait pour être long parce que j'ai la flemme mais en fait c'est fait pour faire comme si on faisait une vraie annonce mais là c'est pas le cas en fait. </p>

    <div class="images">
    <?php for ($i = 0; $i < 7; $i++) {
                    ?>
                        <img src="../view/design/img/logo.png" alt="">
                <?php } ?>
    </div>
</div>

<div class="complement">
    <div class="informations">
        <h2> Informations complémentaires </h2>
        <p> Livraison Paiement Conditions particulières ... </p>
    </div>
    
    <div class="vendeur">
    <h2> Vendeur </h2>
        <p> Nom Contact (Tel) Contact (Mél) Nombre d'annonces postées ... </p>
    </div>
</div>

<footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
</footer>
<body>

</html>

