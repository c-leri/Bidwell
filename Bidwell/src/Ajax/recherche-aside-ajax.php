<?php
// Inclusion du modèle
use Bidwell\Model\Categorie;

require_once __DIR__.'/../../vendor/autoload.php';

$meres = Categorie::readOnlyCategorieMere();
$filles = Categorie::readOnlyCategorieFille();

if (isset($_GET['categories']) && $_GET['categories'] != ''){
    $categorie = $_GET['categories'];
}

$str = "<h2> Catégories </h2>";


for ($i = 0; $i < sizeof($meres); $i++){
    $fillesDeMere = array();


    for($j = 0; $j < sizeof($filles); $j++){
        if ($filles[$j]->getCategorieMere()->getLibelle() == $meres[$i]->getLibelle()){
            $fillesDeMere[] = $filles[$j];
        }
    }


        $str .= '<div class="categoryDropdown">';
        $str .= '<button class="categoryDropdownBtn" onclick="showCategory('.$i.')">' . $meres[$i]->getLibelle() . '</button>';
        $str .= '<div id="cd' . $meres[$i]->getLibelleColle(). '">';
        $str .= '<ul>';


        for($k = 0; $k < sizeof($fillesDeMere); $k++){
            $str .= '<li>';
            $str .= '<input type="checkbox" id="' . $fillesDeMere[$k]->getLibelleColle() . '" onclick="showItems()"';
            
            if (isset($categorie) && $categorie == $fillesDeMere[$k]->getLibelleColle()) {
                $str.= " checked";
            }

            $str .= '></checkbox>';
        
            $str .= '<label for="'.$fillesDeMere[$k]->getLibelleColle() .'">'. $fillesDeMere[$k]->getLibelleColle() .'</label>';
            $str .= '</li>';
        }
        $str .= '</ul>';
        $str .= '<style type="text/css">' . ' #cd' . $meres[$i]->getLibelleColle() . '{ display: none; }';
        $str .= ' #cd' . $meres[$i]->getLibelleColle() . '.active{display: block;}';
        $str .= ' #cd' . $meres[$i]->getLibelleColle() .' ul{list-style-type: none;}';
        $str .= ' #cd' . $meres[$i]->getLibelleColle() .' li input{height:1rem; width:1rem; margin: 0.2rem;}';
        $str .= '</style>';
        $str .= '</div>';
        $str .= '</div>';


}


echo $str;