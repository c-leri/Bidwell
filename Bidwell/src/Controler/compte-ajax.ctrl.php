<?php
use Bidwell\Model\Enchere;

require_once __DIR__.'/../../vendor/autoload.php';

$createur = $_GET['createur'];

//Exécute la requête SQL avec les informations nécessaires à l'affichage
$result = Enchere::readFromCreateurString($createur);


//Initialisation de variable qui sera renvoyée
$str = "";



//Pour chaque ligne de résultat, prépare son affichage en l'ajoutant à la variable renvoyée
    for ($i = 0; $i < sizeof($result); $i++) {

        $str .= "<article>";
        $str .= '<a href="consultation.ctrl.php?id='. $result[$i]->getId() .'">';
        $str .= '<img src="../View/design/img/"'. $result[$i]->getImageURL(0)  .'alt="">';
        $str .= "</a>";
        $str .='<div class="left">';
        $str .= "<h1>" . $result[$i]->getLibelle() . "</h1>";
        $str .= "<h2>". $result[$i]->getCategorie()->getLibelle() . "</h2>";
        $str .= "<ul>";
        $str .=    "<li>" . $result[$i]->getDateDebut()->format("Y-m-d") . "</li>";
        $str .=    "<li>" . $result[$i]->getPrixDepart() . "€</li>";
        $str .=    "<li>" . $result[$i]->getCreateur()->getLogin(). "</li>";
        $str .= "</ul>";
        $str .="</div>";
        $str .='<p class="description">' . $result[$i]->getDescription() . "</p>";
        $str .= "</article>";
    }

//Renvoie le code à afficher
echo $str;
?>