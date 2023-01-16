<?php 

use Bidwell\Model\Enchere;
use Bidwell\Model\Categorie;
use Bidwell\Model\Utilisateur;

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
$infosEnvoie = $_POST["infosEnvoie"] ?? '';
$infosContact = $_POST["infosContact"] ?? '';
$categorielibelle = $_POST["categorie"] ?? '';
$categorie = Categorie::read($categorielibelle);
$description = $_POST["description"] ?? ''; 
$localisation = $_POST["localisation"] ?? ''; 
//CREATION ENCHERE
$enchere = new Enchere($user,$nomAnnonce,$dateDebut,$prixBase,$prixRetrait,$imgsArray[0],$description,$categorie,$infosContact,$infosEnvoie,$localisation);
$enchere->addImage($imgs);
$enchere->create();
$reponse->sucess = 1;
$json = json_encode($reponse);
echo $json;
?>