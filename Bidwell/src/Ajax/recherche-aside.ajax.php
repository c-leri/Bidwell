<?php
// Inclusion du modèle
use Bidwell\Model\Categorie;

require_once __DIR__.'/../../vendor/autoload.php';

$meres = Categorie::readOnlyCategorieMere();
$filles = Categorie::readOnlyCategorieFille();

if (isset($_GET['categories']) && $_GET['categories'] != ''){
    $categorie = $_GET['categories'];
}

$str = "<h2>Catégories</h2>";


for ($i = 0; $i < sizeof($meres); $i++){
    $fillesDeMere = array();

    foreach ($filles as $fille){
        if ($fille->getCategorieMere()->getLibelle() == $meres[$i]->getLibelle()){
            $fillesDeMere[$fille->getLibelleColle()] = $fille;
        }
    }

    $str .= "
        <div class='categoryDropdown'>
            <button class='categoryDropdownBtn' onclick='showCategory($i)'>{$meres[$i]->getLibelle()}</button>
            <div id='cd{$meres[$i]->getLibelleColle()}' " . ((isset($categorie) && isset($fillesDeMere[$categorie])) ? 'class="active"' : '') . ">
                <ul>
    ";

    foreach ($fillesDeMere as $filleDeMere) {
        $str .= "
                    <li>
                        <input type='checkbox' id='{$filleDeMere->getLibelle()}' onclick='showItems()'
                        ".((isset($categorie) && $categorie == $filleDeMere->getLibelleColle()) ? ' checked' : '')." />
                        <label for='{$filleDeMere->getLibelle()}'>{$filleDeMere->getLibelle()}</label>
                    </li>
        ";
    }

    $str .= "
                </ul>
                <style>
                    #cd{$meres[$i]->getLibelleColle()} { display: none; }
                    #cd{$meres[$i]->getLibelleColle()}.active { display: block; }
                    #cd{$meres[$i]->getLibelleColle()} ul { list-style-type: none; }
                    #cd{$meres[$i]->getLibelleColle()} li input { height:1rem; width:1rem; margin: 0.2rem; }
                </style>
            </div>
        </div>
    ";
}


echo $str;