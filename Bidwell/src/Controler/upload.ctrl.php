<?php
require_once __DIR__.'/../../vendor/autoload.php';
//Rajouter ou check dans php ini :
//file_uploads = On
//upload_max_filesize = 16M
//max_file_uploads = 8
//
$reponse = new stdClass();
$reponse->success = 1;
$uploadOk = 1;//http://localhost:3000/Bidwell/data/imgs/10043185297566burger.png"
$target_dir = "./../../data/imgs/";
//Pour chaque fichier contenu dans supertableau $_FILES
//On vérifie la validité du fichier
$fichiers_a_upload = Array();
$indexes = Array();
$urls = array();
for ($i = 0; $i < count($_FILES); $i++) {
    $target_file = $target_dir . basename($_FILES["file".$i]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["file".$i]["tmp_name"]);
    if(!$check) {
        $reponse->errormsg="Veuillez insérer uniquement des images";
        $uploadOk = 0;
        break;
    }
   
    // Check if file already exists, si existe deja on l'upload pas, 
    //en revanche on renvoie quand meme son url sur le serveur pour créer l'enchère après
    array_push($fichiers_a_upload,$target_file);
    array_push($indexes, $i);
    array_push($urls, $target_file);
}
if($uploadOk){
    for ($i = 0; $i < count($fichiers_a_upload); $i++) {
        move_uploaded_file($_FILES["file" . $indexes[$i]]["tmp_name"], $fichiers_a_upload[$i]);
    }
}
$reponse->success = $uploadOk;
$reponse->imgsurls = $urls;
$json = json_encode($reponse);
echo $json;
?>