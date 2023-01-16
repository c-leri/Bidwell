<?php
// Inclusion du modèle
use Bidwell\Model\Enchere;
use Bidwell\Model\Utilisateur;

require_once __DIR__.'/../../vendor/autoload.php';


$page = $_GET['numPage'];  
var_dump($_GET['numPage']);

//Vérifie si le type sélectionné edst "enchère" ou "utilisateur"
if ($_GET['type'] == 'Enchere') {

    $tri = $_GET['tri'] !== 'NULL' ? $_GET["tri"] : 'date';
    $prix = $_GET['prix'] !== 'NULL' ? $_GET["prix"] : 0;



    //Si le type est "enchère", alors vérifie si des catégories ont étées sélectionnées ou non et les transforme en un seul string
    if (isset($_GET["categories"])) {

        $categories = explode(',', $_GET['categories']);



        //Exécute la requête SQL avec les informations nécessaires à l'affichage
        $result = Enchere::readLike($categories, "", $_GET['tri'], $prix, 'ASC', $page, 5);

    } else {

        //Si aucune catégorie sélectionnée
        //Exécute la requête SQL avec les informations nécessaires à l'affichage
        $result = Enchere::readLike([], "", $_GET['tri'], $prix,'ASC', $page, 5);

    }

} else {
    //Si le type est "Utilisateur",
    //Exécute la requête et place le résultat dans $resultat
    $result = Utilisateur::readLike('', 'login', $page, 20);

}

//Initialisation de variable qui sera renvoyée
$str = "";



//Pour chaque ligne de résultat, prépare son affichage en l'ajoutant à la variable renvoyée
if ($_GET['type'] == 'Enchere') {
    for ($i = 0; $i < sizeof($result); $i++) {

        $str .= "<article>";
        $str .= '<a href="consultation.ctrl.php?id='. $result[$i]->getId() .'">';
        $str .= '<img src="../View/design/img/default_image.png" alt="">';
        $str .= "</a>";
        $str .='<div class="left">';
        $str .= "<h1>" . $result[$i]->getLibelle() . "</h1>";
        $str .= "<h3>". $result[$i]->getCategorie()->getLibelle() . "</h3>";
        $str .= "<ul>";
        $str .=    "<li>" . $result[$i]->getDateDebut()->format("Y-m-d") . "</li>";
        $str .=    "<li>" . $result[$i]->getPrixDepart() . "€</li>";
        $str .=    "<li>" . $result[$i]->getCreateur()->getLogin(). "</li>";
        $str .= "</ul>";
        $str .="</div>";
        $str .='<p class="description">' . $result[$i]->getDescription() . "</p>";
        $str .= "</article>";
        }
        $str .= '<div class="numPage">';
        $str .= '<button id="previous" value="' . max(1, $page-1) . '" onclick="changePage(' . max(1, $page-1) .')"><</button>';
        $str .=        '<button id="first" value="' . max(1, $page-2) . '" onclick="changePage(' . max(1, $page-2) .')">' . max(1, $page-2) . '</button>';
        $str .=        '<button id="second" value="' . max(2, $page-1) . '" onclick="changePage(' . max(2, $page-1) . ')">' . max(2, $page-1) . '</button>';
        $str .=        '<button id="third" value="' . max(3, $page) . '" onclick="changePage(' . max(3, $page) . ')">' . max(3, $page) . '</button>';
        $str .=        '<button id="fourth" value="' . max(4, $page+1) . '" onclick="changePage(' . max(4, $page+1) . ')">' . max(4, $page+1) . '</button>';
        $str .=        '<button id="fifth" value="'  . max(5, $page+2) . '" onclick="changePage(' . max(5, $page+2) . ')">'  . max(5, $page+2) . '</button>';
        $str .=        '<button id="next" value="'. max(1, $page+1).'" onclick="changePage(' . max(1, $page+1) . ')">></button>';
        $str .= '</div>';
} else {
    for ($i = 0; $i < sizeof($result); $i++) {
        $str .= "<article>";
        $str .= "<h1>" . $result[$i]->getLogin() . "</h1>";
        $str .= "<h1>" . $result[$i]->getNom() . "</h1>";
        $str .= "</article>";
    }
}
//Renvoie le code à afficher
echo $str;
?>