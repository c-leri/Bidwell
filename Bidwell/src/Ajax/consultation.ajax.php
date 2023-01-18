<?php
use Bidwell\Model\Enchere;

require_once __DIR__.'/../../vendor/autoload.php';


$enchere = Enchere::read($_GET['id']);

$prixRetrait = $_GET['prixRetrait'];
$prixMax = $_GET['prixHaut'];
$instantDerniereEnchere = $_GET['instantDerniereEnchere'];
$prixact = $enchere->getPrixCourant();

$pourcent = ($prixMax > $prixRetrait) ? (74 - (($prixact - $prixRetrait)/($prixMax - $prixRetrait)) * 74) : 0;
$affichage = round($pourcent, 2, PHP_ROUND_HALF_DOWN);

$now = new DateTime();
$tempsRes = $now->diff($enchere->getInstantFin());
$date = $tempsRes->format("%H:%i:%s");

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

    <button id='encherebutton' onclick='encherir(event)'><span>Enchérir</span></button>

    
    <div class='temps'>
        <p id='dateTitle'>L'enchère se terminera dans</p>
        <p id='temps'>$date</p>
    </div>

    <div class='prix'>
        <div>
            <p>Prix de retrait</p>
            <p id='min'>{$prixRetrait}€</p>
        </div>
    <div>

    <p>Prix Actuel</p>
    <p id='act'>{$prixact}€</p>

    <div>
        <div>
            <p>Prix de départ</p>
            <p id='max'>{$prixMax}€</p>
        </div>
    </div>
    
    <input type='hidden' id='instantDerniereEnchere' name='instantDerniereEnchere' value='$instantDerniereEnchere'>
    <input type='hidden' id='instantFin' name='instantFin' value='{$enchere->getInstantFin()->getTimestamp()}'>
    <input type='hidden' id='dateDebut' name='dateDebut' value='{$enchere->getDateDebut()->getTimestamp()}'>
";