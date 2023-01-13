<?php
//Inclusion de la base de donnée
require(__DIR__ . "/../model/Enchere.class.php");


$ordre = $_GET['ordre'] === 'ASC' ? 'ASC' : 'DESC';

$result = Enchere::readLike("", "date", 1, 12);

//Initialisation de variable qui sera renvoyée
$str = "";



//Pour chaque ligne de résultat, prépare son affichage en l'ajoutant à la variable renvoyée
    for ($i = 0; $i < sizeof($result); $i++) {
        $str .= "<article>";
        $str .= '<a href="consultation.ctrl.php?id="'. $result[$i][1] .'>'; //Changer en lien de l
        $str .= '<img src="' . "../view/design/img/default_image.png" . '">'; //Changer en lien de l'image correspondante
        $str .= "</a>";
        $str .= "<h1>" . $result[$i][2] . "</h1>";
        $str .= '<div class="variablesEnchere">';
        $str .= '<p class="temps-restant">' . $result[$i][3] . "</p>";
        $str .= '<p class="prix-actuel">' . $result[$i][4] . "€</p>";
        $str .= "</div>";
        $str .= "</article>";
    }
//Renvoie le code à afficher
echo $str;
?>