<?php

use Bidwell\Model\Categorie;
use Bidwell\Model\Enchere;
use Bidwell\Model\Utilisateur;

require_once __DIR__ . '/../../vendor/autoload.php';

$reponse = new stdClass();

session_start();
$user = Utilisateur::read($_SESSION["login"]);
session_write_close();

$nomAnnonce = $_POST["nom"] ?? '';
$dateDebut = new DateTime();
$dateDebut->modify('+1 minutes');
$prixBase = $_POST["prixbase"] ?? '';
$prixRetrait = $_POST["prixretrait"] ?? '';
$imgs = $_POST["imgs"] ?? '';
$imgsArray = explode(",", $imgs);//Array contenant les urls des images

$infosEnvoistr = explode(",",$_POST["infosEnvoi"]) ?? '';
$infosEnvoi = array();
array_push($infosEnvoi, filter_var($infosEnvoistr[0], FILTER_VALIDATE_BOOLEAN), filter_var($infosEnvoistr[1], FILTER_VALIDATE_BOOLEAN));
$infosContact = array();
$infosContactStr = explode(",",$_POST["infosContact"]) ?? '';
array_push($infosContact, filter_var($infosContactStr[0], FILTER_VALIDATE_BOOLEAN), filter_var($infosContactStr[1], FILTER_VALIDATE_BOOLEAN));
$categorielibelle = $_POST["categorie"] ?? '';
$categorie = Categorie::read($categorielibelle);
$description = $_POST["description"] ?? ''; 
$codePostal = $_POST["codePostal"] ?? ''; 
//CREATION ENCHERE
$enchere = new Enchere($user,$nomAnnonce,$dateDebut,$prixBase,$prixRetrait,$imgsArray[0],$description,$categorie,$infosContact,$infosEnvoi,$codePostal);
for ($i = 1; $i < count($imgsArray);$i++){
    $enchere->addImage($imgsArray[$i]);
}
$enchere->create();
$reponse->sucess = 1;
$json = json_encode($reponse);
echo $json;