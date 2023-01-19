<?php 

use Bidwell\Model\Categorie;
require_once __DIR__.'/../../vendor/autoload.php';
$reponse = new stdClass();
$arrayCategoriesMeres=Categorie::readOnlyCategorieMere();
$arrayrep = array();
foreach($arrayCategoriesMeres as $categorieMere){
    $arrayrep[] = "---------" . $categorieMere->getLibelle() . "---------";
    $arrayCategoriesFilles = Categorie::readFromCategorieMere($categorieMere);
    foreach($arrayCategoriesFilles as $categorieFille){
        $arrayrep[] = $categorieFille->getLibelle();
    }
    
}
$reponse->array = $arrayrep;
$json = json_encode($reponse);
echo $json;