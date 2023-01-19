<?php
use Bidwell\Model\Enchere;
use Bidwell\Model\Utilisateur;

require_once __DIR__.'/../../vendor/autoload.php';


$enchere = Enchere::read($_GET['id']);

session_start();
$login = $_SESSION['login'] ?? '';
session_write_close();

$nbJetons = ($login !== '') ? Utilisateur::read($login)->getNbJetons() : null;

$prixRetrait = round($enchere->getPrixRetrait(), 2);
$prixHaut = round($enchere->getPrixHaut(), 2);
$prixact = round($enchere->getPrixCourant(), 2);

$pourcent = (74 - (($prixact - $prixRetrait)/($prixHaut - $prixRetrait)) * 74);
$affichage = round($pourcent, 2, PHP_ROUND_HALF_DOWN);

$disabled = 'disabled';

$message = '';
$messageDisplay = 'none';
$messageColor = 'var(--couleur-jaune)';

$now = new DateTime();

// avant enchère
if ($now < $enchere->getDateDebut()) {
    $dateTitle = "L'enchère commencera dans";
    $date = $now->diff($enchere->getDateDebut())->format("%H:%I:%S");
// enchère en cours
} else if ($now < $enchere->getInstantFin()) {
    $disabled = '';
    $dateTitle = "L'enchère se terminera dans";
    $date = $now->diff($enchere->getInstantFin())->format("%H:%I:%S");
    if ($enchere->getDerniereEnchere() !== null && $enchere->getDerniereEnchere()->getUtilisateur()->getLogin() == $login) {
        $message = "Vous êtes en tête de l'enchère !";
        $messageDisplay = 'block';
    }
// après enchère
} else {
    $createur = $enchere->getCreateur();

    $email = ($enchere->getInfosContact()[0]) ? $createur->getEmail() : '';
    $tel = ($enchere->getInfosContact()[1]) ? $createur->getNumeroTelephone() : '';

    $contact = "Veuillez contacter le vendeur par " . (($email != '')
        ? (($tel != '') ? "mail à $email ou par téléphone, au $tel" : "mail à $email")
        : "téléphone au $tel");

    $contact .= " pour vous mettre d'accord sur la transation et " . (($enchere->getInfosEnvoi()[0])
        ? (($enchere->getInfosEnvoi()[1]) ? "l'envoi ou la remise en main propre de l'article." : "la remise en main propre de l'article.")
        : "l'envoi de l'article.");

    $dateTitle = "L'enchère est terminée";
    $date = '';
    $messageDisplay = 'block';
    if ($enchere->getDerniereEnchere() !== null && $enchere->getDerniereEnchere()->getUtilisateur()->getLogin() == $login){
        $message = "Vous avez remporté l'enchère ! $contact";
    } else {
        $message = "Vous n'avez pas remporté cette enchère.";
        $messageColor = 'var(--couleur-rouge)';
    }

    // on met la barre de progression du prix à son état final (vide)
    $affichage = 74;

    // on met le prix actuel au prix auquel est partie l'enchère (prix de retrait si personne n'a enchéri)
    $prixact = ($enchere->getDerniereEnchere() !== null)
        ? round($enchere->getDerniereEnchere()->getMontantDerniereEnchere(), 2)
        : round($enchere->getPrixRetrait(), 2);
}

$str = "
    <svg class='circle-container' viewBox='2 -2 28 36'>
        <linearGradient id='gradient'>
            <stop class='stop1' offset='0%' />
            <stop class='stop2' offset='100%' />
        </linearGradient>
        <circle class='circle-container__background' r='16' cx='16' cy='16'>
        </circle>
        <circle class='circle-container__progress' id='circle-container__progress' r='16' cx='16' cy='16' style='stroke-dashoffset:$affichage'
            shape-rendering='geometricPrecision'>
        </circle>
    </svg>

    <button id='encherebutton' onclick='encherir()' $disabled><span>Enchérir</span></button>
";

if (isset($nbJetons)) {
    $str .= "
        <div class='jetons'>
            <p>Vos Jetons</p>
            <p>$nbJetons</p>
        </div>
    ";
}

$str .= "
    <div class='temps'>
        <p id='dateTitle'>$dateTitle</p>
        <p id='temps'>$date</p>
    </div>

    <div class='prix'>
        <div>
            <p>Prix de retrait</p>
            <div>
            <p id='min'>{$prixRetrait}€</p>
            </div>
        </div>
        <div>
            <p>Prix Actuel</p>
            <div>
            <p id='act'>{$prixact}€</p>
            </div>
        </div>
        <div>
            <p>Prix de départ</p>
            <div>
            <p id='max'>{$prixHaut}€</p>
            </div>
        </div>
    </div>
    <p id='message' style='display: $messageDisplay; color: $messageColor;'>$message</p>
    
    <input type='hidden' id='instantDerniereEnchere' name='instantDerniereEnchere' value='{$enchere->getInstantDerniereEnchere()->getTimestamp()}'>
    <input type='hidden' id='instantFin' name='instantFin' value='{$enchere->getInstantFin()->getTimestamp()}'>
    <input type='hidden' id='dateDebut' name='dateDebut' value='{$enchere->getDateDebut()->getTimestamp()}'>
";

echo $str;