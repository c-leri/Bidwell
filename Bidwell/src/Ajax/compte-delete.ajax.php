<?php
use Bidwell\Model\Utilisateur;
use Bidwell\Model\Enchere;

require_once __DIR__.'/../../vendor/autoload.php';

$login = $_GET['login'];

$result = Enchere::readFromCreateurString($login);

$impossible = false;

for ($i = 0; $i < sizeof($result); $i++) {
    if ($result[$i]->getDateDebut() >= new DateTime() && $result[$i]->getInstantFin() <= new DateTime()) {
        $impossible == true;
    }
}

echo $impossible;

if ($impossible == false){
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
