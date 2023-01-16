<?php
// Inclusion du framework
use Bidwell\Framework\View;
use Bidwell\Model\Enchere;

require_once __DIR__.'/../../vendor/autoload.php';

////////////////////////////////////////////////////////////////////////////
// Récupération des informations à afficher
////////////////////////////////////////////////////////////////////////////
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id == null) {
    throw new Exception("L'enchère que vous essayer de consulter n'existe pas ou a été supprimée");
} else {
    $enchere = Enchere::read($id);

    $nom = $enchere->getLibelle();

    $prixdep = $enchere->getPrixDepart();
    $prixact = $enchere->getPrixCourant();
    $prixfin = $enchere->getPrixRetrait();

    //Faudra corriger le temps restant j'invoque l'excuse de 4 heure du matin pour pas faire
    //Mais j'imagine qu'il faudra faire une fonction directement dans Enchere pour notamment vérifier que c'est pas dans le futur
    $maintenant = new DateTime();
    $tempsRes = (int)$maintenant->format('Uv') - (int)$enchere->getInstantFin()->format('Uv');

    // Calcul du nombre à envoyer à l'affichage pour la barre d'enchère
    // Vous pouvez redéfinir prixact en une valeur comprise netre prixfin et prixdep pour tester
    //$prixact = 400;
    $pourcent = (1 - ($prixact - $prixfin) / ($prixdep - $prixfin) ) * 74;
    $affichage = round($pourcent, 1, PHP_ROUND_HALF_DOWN);

    $description = $enchere->getDescription();
    $images = $enchere->getImages();  
    $createur = $enchere->getCreateur();

    $autorisations = explode(",", $enchere->getInfosContact());
    if ($autorisations[0] == true){
        $mail = $enchere->getCreateur()->getEmail();
    } else {
        $mail = "Le créateur de l'enchère n'a pas souahité partager son e-Mail";
    }

    if ($autorisations[1] == true){
        $tel = $enchere->getCreateur()->getNumeroTelephone();
    } else {
        $tel = "Le créateur de l'enchère n'a pas souahité partager son numéro de téléphone";
    }
    

    
    $informations = $enchere->getInfosEnvoie();
    if ($informations[0] == true){
        $place = "Le créateur de l'enchère est prêt à remettre le bien en main propre";
    } else {
        $place = "Le créateur de l'enchère n'est PAS prêt à remettre le bien en main propre";
    }

    if ($autorisations[1] == true){
        $dist = "Le créateur de l'enchère est prêt à envoyer le bien par colis";
    } else {
        $dist = "Le créateur de l'enchère n'est PAS prêt à envoyer le bien par colis";
    }

    $localisation = $enchere->getLocalisation();


}

////////////////////////////////////////////////////////////////////////////
// Construction de la vue
////////////////////////////////////////////////////////////////////////////
$view = new View();

$view->assign('nom', $nom);
$view->assign('prixDepart', $prixdep);
$view->assign('prixActuel', $prixact);
$view->assign('prixRetrait', $prixfin);
$view->assign('tempsRestant', $tempsRes);
$view->assign('affichage', $affichage);

$view->assign('description',  $description);
$view->assign('images', $images);

$view->assign('createur', $createur);

$view->assign('mail', $mail);
$view->assign('tel', $tel);

$view->assign('place', $place);
$view->assign('dist', $dist);

$view->assign('localisation', $localisation);





// Charge la vue
$view->display("consultation.view.php");