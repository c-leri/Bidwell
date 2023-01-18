<?php
// Inclusion du modèle
use Bidwell\Model\Enchere;

require_once __DIR__.'/../../vendor/autoload.php';

$ordre = $_GET['ordre'] === 'ASC' ? 'ASC' : 'DESC';


$result = Enchere::readLike([], "", "date", 0, $ordre, 1, 12);


//Initialisation de variable qui sera renvoyée
$str = "";


//Pour chaque ligne de résultat, prépare son affichage en l'ajoutant à la variable renvoyée
    for ($i = 0; $i < sizeof($result); $i++) {
       
        $str .= "<article>";
        $str .= '<a href="consultation.ctrl.php?id='. $result[$i]->getId() .'">'; //Changer en lien de l'annonce
        $str .= '<img src="'. $result[$i]->getImageURL(0).'">'; //Changer en lien de l'image correspondante
        $str .= "</a>";
        $str .='<div class="variablesEnchere">';
        $str .= "<h1>" . $result[$i]->getLibelle() . "</h1>";
        $str .= "<ul>";
        $str .=    "<li>". $result[$i]->getCategorie()->getLibelle() . "</li>"; 
        $str .=    "<li>" . $result[$i]->getPrixDepart() . "€</li>";       
        $str .=    "<li>" . $result[$i]->getDateDebut()->format("Y-m-d") . "</li>";
        $str .=    "<li>" . $result[$i]->getCreateur()->getLogin(). "</li>";
        $str .= "</ul>";
        $str .="</div>";
        $str .= "</article>";
    }
//Renvoie le code à afficher
echo $str;