<?php
// Inclusion du framework
use Bidwell\Framework\View;
use Bidwell\Model\Enchere;
use Bidwell\Model\Utilisateur;

require_once __DIR__.'/../../vendor/autoload.php';

////////////////////////////////////////////////////////////////////////////
// Récupération des informations à afficher
////////////////////////////////////////////////////////////////////////////
session_start();
$login = $_SESSION['login'] ?? '';
session_write_close();

$nbJetons = ($login !== '') ? Utilisateur::read($login)->getNbJetons() : null;

$id = $_GET['id'] ?? null;

if ($id == null) {
    throw new Exception("L'enchère que vous essayer de consulter n'existe pas ou a été supprimée");
} else {
    $enchere = Enchere::read($id);

    $nom = $enchere->getLibelle();

    $prixdep = round($enchere->getPrixHaut(), 2);

    $prixfin = round($enchere->getPrixRetrait(), 2);

    $maintenant = new DateTime();

    $message = '';
    $messageDisplay = 'none';
    $messageColor = 'var(--couleur-jaune)';
    
    // avant enchère
    if ($maintenant < $enchere->getDateDebut()) {
        $tempsRes = $maintenant->diff($enchere->getDateDebut());
        $prixact = $prixdep;
        $dateTitle = "L'enchère commencera dans ";
        $date = $tempsRes->format("%H:%I:%S");
        $button = 'disabled';
    } else {
        $prixact = round($enchere->getPrixCourant(), 2);
        $fin = $enchere->getInstantFin();

        // enchère en cours
        if ($maintenant >= $enchere->getDateDebut() && $maintenant < $enchere->getInstantFin()) {
            $tempsRes = $maintenant->diff($fin);
            $dateTitle = "L'enchère se terminera dans ";
            $date = $tempsRes->format("%H:%I:%S");
            $button = '';
            if ($enchere->getDerniereEnchere() !== null && $enchere->getDerniereEnchere()->getUtilisateur()->getLogin() == $login) {
                $message = "Vous êtes en tête de l'enchère !";
                $messageDisplay = 'block';
            }
        // enchère terminée
        } else {
            $createur = $enchere->getCreateur();

            $email = ($enchere->getInfosContact()[0]) ? $createur->getEmail() : '';
            $tel = ($enchere->getInfosContact()[1]) ? $createur->getNumeroTelephone() : '';

            $contact = "Veuillez contacter le vendeur par " . (($email != '')
                ? (($tel != '') ? "mail à $email ou par téléphone au $tel" : "mail à $email")
                : "téléphone au $tel");

            $contact .= " pour vous mettre d'accord sur la transation et " . (($enchere->getInfosEnvoi()[0])
                ? (($enchere->getInfosEnvoi()[1]) ? "l'envoi ou la remise en main propre de l'article." : "la remise en main propre de l'article.")
                : "l'envoi de l'article.");

            $prixact = $prixfin;
            $dateTitle = "L'enchère est terminée";
            $button = 'disabled';
            $date = '';
            $messageDisplay = 'block';
            if ($enchere->getDerniereEnchere() !== null && $enchere->getDerniereEnchere()->getUtilisateur()->getLogin() == $login){
                $message = "Vous avez remporté l'enchère ! $contact";
            } else {
                $message = "Vous n'avez pas remporté cette enchère.";
                $messageColor = 'var(--couleur-rouge)';
            }
        }
    }

    // Calcul du nombre à envoyer à l'affichage pour la barre d'enchère
    // Vous pouvez redéfinir prixact en une valeur comprise netre prixfin et prixdep pour tester
    //$prixact = 400;
    $pourcent = (1 - ($prixact - $prixfin) / ($prixdep - $prixfin) ) * 74;
    $affichage = round($pourcent, 1, PHP_ROUND_HALF_DOWN);

    $description = $enchere->getDescription();
    $images = $enchere->getImages();  
    $createur = $enchere->getCreateur();

    $autorisations = $enchere->getInfosContact();
    if ($autorisations[0]){
        $mail = $enchere->getCreateur()->getEmail();
    } else {
        $mail = "Le créateur de l'enchère n'a pas souahité partager son e-Mail";
    }

    if ($autorisations[1]){
        $tel = $enchere->getCreateur()->getNumeroTelephone();
    } else {
        $tel = "Le créateur de l'enchère n'a pas souhaité partager son numéro de téléphone";
    }
    

    
    $informations = $enchere->getInfosEnvoi();
    if ($informations[0]){
        $place = "Le créateur de l'enchère est prêt à remettre le bien en main propre";
    } else {
        $place = "Le créateur de l'enchère n'est PAS prêt à remettre le bien en main propre";
    }

    if ($informations[1]){
        $dist = "Le créateur de l'enchère est prêt à envoyer le bien par colis";
    } else {
        $dist = "Le créateur de l'enchère n'est PAS prêt à envoyer le bien par colis";
    }

    $codePostal = $enchere->getCodePostal();


}

////////////////////////////////////////////////////////////////////////////
// Construction de la vue
////////////////////////////////////////////////////////////////////////////
$view = new View();

$view->assign('connected', isset($login));

$view->assign('enchere', $enchere);

$view->assign('nom', $nom);
$view->assign('prixDepart', $prixdep);
$view->assign('prixActuel', $prixact);
$view->assign('prixRetrait', $prixfin);
$view->assign('tempsRestant', $date);
$view->assign('dateTitle', $dateTitle);
$view->assign('affichage', $affichage);
$view->assign('button', $button);

$view->assign('description',  $description);
$view->assign('addresseImage', Enchere::ADRESSE_IMAGES);
$view->assign('images', $images);

$view->assign('createur', $createur);
$view->assign('login', $login);

$view->assign('mail', $mail);
$view->assign('tel', $tel);

$view->assign('place', $place);
$view->assign('dist', $dist);

$view->assign('localisation', $codePostal);

$view->assign('message', $message);
$view->assign('messageDisplay', $messageDisplay);
$view->assign('messageColor', $messageColor);

// 5% du prix de départ avec 1 jeton = 1 euro
$view->assign('prixJetons', $enchere->getPrixDepart() * 0.05);
$view->assign('nbJetons', $nbJetons);

$view->assign('instantDerniereEnchere',$enchere->getInstantDerniereEnchere()->getTimestamp());
$view->assign('instantFin', $enchere->getInstantFin()->getTimestamp());
$view->assign('dateDebut', $enchere->getDateDebut()->getTimestamp());


// Charge la vue
$view->display("consultation.view.php");