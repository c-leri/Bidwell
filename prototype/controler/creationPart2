<?php 
// insert into categorie values("jesuisunecategorie",NULL);
// Inclusion du framework
include_once(__DIR__."/../framework/View.class.php");
require(__DIR__."/../model/DAO.class.php");
require(__DIR__."/../model/Categorie.class.php");

$reponse = new stdClass();
// récupération du dao
$dao = DAO::get();

// récupération de la table de résultat
$table = $dao->execute('SELECT libelle FROM Categorie')->fetchAll();
$reponse->arrayCategories=$table;
$json = json_encode($reponse);
echo $json;
?>