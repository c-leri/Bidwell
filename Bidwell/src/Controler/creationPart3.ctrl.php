<?php 

use Bidwell\Bidwell\Model\Utilisateur;
use Bidwell\Bidwell\Model\Enchere;
use Bidwell\Bidwell\Model\Categorie;

require_once __DIR__.'/../../vendor/autoload.php';

$reponse = new stdClass();
session_start();

$user = Utilisateur::read($_SESSION["login"]);
$nomAnnonce = $_POST["nom"] ?? '';
$dateDebut = new DateTime();
$dateDebut->add(DateInterval::createFromDateString('24 hour'));
$prixBase = $_POST["prixbase"] ?? '';
$prixRetrait = $_POST["prixretrait"] ?? '';
$imgs = $_POST["imgs"] ?? '';
$imgsArray = explode(",", $imgs);//Array contenant les urls des images
//$imgs = str_replace(","," ",$imgs); a décommenter si on veut mettre des espaces plutot que des virgules
//Faire addimage
$categorielibelle = $_POST["categorie"] ?? '';
$categorie = Categorie::read($categorielibelle);
$description = $_POST["description"] ?? ''; 
//CREATION ENCHERE
$enchere = new Enchere($user,$nomAnnonce,$dateDebut,$prixBase,$prixRetrait,$imgsArray[0],$description,$categorie);
$enchere->addImage($imgs);
$enchere->create();
$reponse->sucess = 1;
$json = json_encode($reponse);
echo $json;
?>