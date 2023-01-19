<?php
use Bidwell\Model\Enchere;

require_once __DIR__.'/../../vendor/autoload.php';


$enchere = Enchere::read($_GET['id']);

session_start();
$login = $_SESSION['login'] ?? '';
session_write_close();

$prixRetrait = round($enchere->getMontantDerniereEnchere(), 2);
$prixHaut = round($enchere->getMontantDerniereEnchere() + $enchere->getPrixDepart() * 0.05, 2);
$prixact = $enchere->getPrixCourant();

$pourcent = ($prixRetrait < $prixact) ? (74 - (($prixact - $prixRetrait)/($prixHaut - $prixRetrait)) * 74) : 0;
$affichage = round($pourcent, 2, PHP_ROUND_HALF_DOWN);

$disabled = 'disabled';
$message = '';

$now = new DateTime();

if ($now < $enchere->getDateDebut()) {
    $dateTitle = "L'enchère commencera dans";
    $date = $now->diff($enchere->getDateDebut())->format("%H:%I:%S");
} else if ($now < $enchere->getInstantFin()) {
    $disabled = '';
    $dateTitle = "L'enchère se terminera dans";
    $date = $now->diff($enchere->getInstantFin())->format("%H:%I:%S");
} else {
    $dateTitle = "L'enchère est terminée";
    $date = '';
    $message = ($enchere->getDerniereEnchere() !== null && $enchere->getDerniereEnchere()->getUtilisateur()->getLogin() === $login)
        ? "Vous avez remporté l'enchère ! Contactez le vendeur pour préparer sa livraison."
        : "Vous n'avez pas remporté cette enchère.";
}

echo "
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
    <p id='message'>$message</p>
    
    <input type='hidden' id='instantDerniereEnchere' name='instantDerniereEnchere' value='{$enchere->getInstantDerniereEnchere()->getTimestamp()}'>
    <input type='hidden' id='instantFin' name='instantFin' value='{$enchere->getInstantFin()->getTimestamp()}'>
    <input type='hidden' id='dateDebut' name='dateDebut' value='{$enchere->getDateDebut()->getTimestamp()}'>
";