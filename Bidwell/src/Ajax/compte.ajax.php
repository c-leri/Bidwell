<?php
use Bidwell\Model\Enchere;

require_once __DIR__.'/../../vendor/autoload.php';

$createur = $_GET['createur'];

//Exécute la requête SQL avec les informations nécessaires à l'affichage
$result = Enchere::readFromCreateurString($createur);

if(isset($_GET['suppr'])){
    $supprime=Enchere::read($_GET['suppr']);
    $supprime->delete();
}
//Initialisation de variable qui sera renvoyée
$str = "";



//Pour chaque ligne de résultat, prépare son affichage en l'ajoutant à la variable renvoyée
    for ($i = 0; $i < sizeof($result); $i++) {

        $str .= "<article>";
        $str .= '<a href="consultation.ctrl.php?id='. $result[$i]->getId() .'">';
        $str .= '<img src="'. $result[$i]->getImageURL(0)  .'" alt="">';
        $str .= "</a>";
        $str .='<div class="variablesEnchere">';
        $str .= "<h1>" . $result[$i]->getLibelle() . "</h1>";
        $str .= "<ul>";
        $str .=    "<li>". $result[$i]->getCategorie()->getLibelle() . "</li>";  
        $str .=    "<li>" . $result[$i]->getPrixDepart() . "€</li>";      
        $str .=    "<li>" . $result[$i]->getDateDebut()->format("Y-m-d") . "</li>";
        $str .=    "<li>" . $result[$i]->getCreateur()->getLogin(). "</li>";
        $str .= "</ul>";
        $str .= "<br>";
        $str .= '<button id="suppressionenchere" onclick="supprenchere('.$result[$i]->getId().')">Supprimer</button>';
        $str .="</div>";
        $str .= "</article>";
    }

//Renvoie le code à afficher
echo $str;