<?php
namespace Bidwell\Bidwell\Controler;

// Inclusion du modèle
use Bidwell\Bidwell\Model\Categorie;

require_once __DIR__.'/../../vendor/autoload.php';

$meres = Categorie::readOnlyCategorieMere();
$filles = Categorie::readOnlyCategorieFille();


$str = "<h2> Catégories </h2>";


for ($i = 0; $i < sizeof($meres); $i++){
    $fillesDeMere = array();


    for($j = 0; $j < sizeof($filles); $j++){
        if ($filles[$j]->getCategorieMere()->getLibelle() == $meres[$i]->getLibelle()){
            $fillesDeMere[] = $filles[$j];
        }
    }

    if (sizeof($fillesDeMere) != 0){

        $str .= '<div class="categoryDropdown">';
        $str .= '<button class="categoryDropdownBtn" onclick="showCategory('.$i.')">' . $meres[$i]->getLibelle() . '</button>';
        $str .= '<div id="cd' . $meres[$i]->getLibelleColle(). '">';


        for($k = 0; $k < sizeof($fillesDeMere); $k++){
            $str .= '<input type="checkbox" id="' . $fillesDeMere[$k]->getLibelleColle() . '" onclick="showItems()"></checkbox>';
            $str .= '<label for="'.$fillesDeMere[$k]->getLibelleColle() .'">'. $fillesDeMere[$k]->getLibelle() .'</label>';
        }
        $str .= '<style type="text/css">' . ' #cd' . $meres[$i]->getLibelleColle() . '{ display: none; }';
        $str .= ' #cd' . $meres[$i]->getLibelleColle() . '.active{display: block;}';

        $str .= '</style>';
        $str .= '</div>';
        $str .= '</div>';



    }

}


echo $str;