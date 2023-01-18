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
//echo $imgsArray[0];
//$imgs = str_replace(","," ",$imgs); a décommenter si on veut mettre des espaces plutot que des virgules
$infosEnvoie = $_POST["infosEnvoie"] ?? '';
$infosContact = $_POST["infosContact"] ?? '';
$categorielibelle = $_POST["categorie"] ?? '';
$categorie = Categorie::read($categorielibelle);
$description = $_POST["description"] ?? '';
$localisation = $_POST["localisation"] ?? '';
//CREATION ENCHERE
$enchere = new Enchere($user, $nomAnnonce, $dateDebut, $prixBase, $prixRetrait, $imgsArray[0], $description, $categorie, $infosContact, $infosEnvoie, $localisation);
for ($i = 1; $i < count($imgsArray); $i++) {
    $enchere->addImage($imgsArray[$i]);
}
$enchere->create();
$reponse->sucess = 1;
$json = json_encode($reponse);
echo $json;