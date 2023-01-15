<?php 

use Bidwell\Bidwell\Model\Categorie;
require_once __DIR__.'/../../vendor/autoload.php';

$reponse = new stdClass();
$reponse->arrayCategories= Categorie::readLibelleCategorieFilles();
$json = json_encode($reponse);
echo $json;
?>