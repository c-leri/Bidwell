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
    <title>Vendre un article</title>
    <link rel="stylesheet" href="../view/design/styleGeneral.css">
    <link rel="stylesheet" href="../view/design/styleMenu.css">
    <link rel="stylesheet" href="../view/design/styleFooter.css">
    <link rel="stylesheet" href="../view/design/styleConsultation.css">

</head>
<!-- Menu -->
<header> 
    <nav id="navbar-top">
    <div class="nav-left">
        <a href="main.ctrl.php">
            <img src="../view/design/img/logo.png" alt="logo">
        </a>
    </div>

    <div id="nav-right-cancel">
            <a href="signup.ctrl.php">
                Annuler
            </a>
        </div>

    </nav>
</header>
<body>
<div class="retour">
    <button type="submit">Annuler</button>
</div>

<div class="top">
    <div class="presentation">
    <input type="file" id="mainImage"  accept="image/png, image/jpeg">
     <a href="#" onclick="openFile();return;">Ajouter une image</a>

        <p> Nom de l'article </p>
    </div>
</div>



<footer>
        <?php include(__DIR__ . '/footer.viewpart.php') ?>
</footer>
<body>

<script>
  function openFile()
{
  document.getElementById("mainImage").click();
}
</script>
    
<!-- Explications :

Création d'un input qui permet de chercher un fichier de type png ou jpeg
Cet input est mit en invisible parce que sinon c'est moche et on le remplace par un lien qui est plus facile
à modifier en CSS

Lien qui, une fois cliqué, active le script "openFile()"

Ce script utilise .click sur l'input créé
-> .click est une méthode qui active un événement "click" 

-> L'input étant de type "file", cela ouvre une fenêtre contextuelle qui permet d'insérer une image

Si vous supprimez style="display:none", vous pouvez voir que lorsqu'une image est ajoutée, 
elle est bien sauvegardée et ça fait pas rien
-->
</html>