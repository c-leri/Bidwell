<?php
require_once __DIR__.'/../../vendor/autoload.php';
//Rajouter ou check dans php ini :
//file_uploads = On
//upload_max_filesize = 16M
//max_file_uploads = 8
//
$reponse = new stdClass();
$reponse->success = 1;
$uploadOk = 1;
$target_dir = __DIR__ . "/../../data/img/";

//$img = str_replace("C:\Users\cleme\SAE3", "../../..", $img);
//Pour chaque fichier contenu dans supertableau $_FILES
//On vérifie la validité du fichier
$fichiers_a_upload = Array();
$indexes = Array();
$imgs = array();
for ($i = 0; $i < count($_FILES); $i++) {
    $target_file = $target_dir . trim($_FILES["file".$i]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["file".$i]["tmp_name"]);
    if(!$check) {
        $reponse->errormsg="Veuillez insérer uniquement des images";
        $uploadOk = 0;
        break;
    }
    $fichiers_a_upload[] = $target_file;
    $indexes[] = $i;
    $imgs[] = trim($_FILES["file" . $i]["name"]);
}
if($uploadOk){
    for ($i = 0; $i < count($fichiers_a_upload); $i++) {
        move_uploaded_file($_FILES["file".$indexes[$i]]["tmp_name"], $fichiers_a_upload[$i]);
    }
}
$reponse->success = $uploadOk;
$reponse->imgs = $imgs;
$json = json_encode($reponse);
echo $json;