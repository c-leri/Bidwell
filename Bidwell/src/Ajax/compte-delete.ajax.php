<?php
use Bidwell\Model\Utilisateur;
use Bidwell\Model\Enchere;

require_once __DIR__.'/../../vendor/autoload.php';

$login = $_GET['login'];

$result = Enchere::readFromCreateurString($login);

$impossible = false;
$maintenant = new DateTime();


for ($i = 0; $i < sizeof($result); $i++) {    
    if ($result[$i]->getDateDebut() <= $maintenant && $result[$i]->getInstantFin() >= $maintenant) {
        $impossible = true;
        
    }
}

$text = var_export($impossible, true);

if ($text == "false"){
    
    for ($i = 0; $i < sizeof($result); $i++) {
        $result[$i]->delete();
    } 

    $supprime = Utilisateur::read($login);
    $supprime->delete();

    session_start();

    // Fermeture
    session_destroy();
    
    echo 'OK';

} else {
    echo "erreur: enchère en cours";
}

?>
