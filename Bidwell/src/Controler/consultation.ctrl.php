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
    $pourcent = (1 - ($prixact - $prixfin) / ($prixdep - $prixfin) ) * 74;
    $affichage = round($pourcent, 1, PHP_ROUND_HALF_DOWN);

    $description = $enchere->getDescription();
    $images = $enchere->getImages();

    //$infos = $enchere->getLivraison();

    $createur = $enchere->getCreateur();
    //$autorisations = $enchere->getAutorisation();


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
$view->assign('description', $description);
$view->assign('images', $images);
$view->assign('createur', $createur);


// Charge la vue
$view->display("consultation.view.php");