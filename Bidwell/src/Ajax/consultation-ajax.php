<?php
use Bidwell\Model\Enchere;
use Bidwell\Util\Helper;

require_once __DIR__.'/../../vendor/autoload.php';


$enchere = Enchere::read($_GET['id']);

$prixRetrait = $_GET['prixRetrait'];
$prixMax = $_GET['prixHaut'];
$instantDerniereEnchere = $_GET['instantDerniereEnchere'];
$prixact = $enchere->getPrixCourant();

$pourcent = ($prixMax > $prixRetrait) ? (($prixact / $prixMax) * 74) : 74;
$affichage = round($pourcent, 2, PHP_ROUND_HALF_DOWN);

$now = new DateTime();
$tempsRes = $now->diff($enchere->getInstantFin());
$date = $tempsRes->format("%h:%i:%s");

$str = '';

$str .= '<svg class="circle-container" viewBox="2 -2 28 36">';
$str .=     '<linearGradient id="gradient">';
$str .=         '<stop class="stop1" offset="0%" />';
$str .=         '<stop class="stop2" offset="100%" />';
$str .=     '</linearGradient>';
$str .=     '<circle class="circle-container__background" r="16" cx="16" cy="16">';
$str .=     '</circle>';
$str .=     '<circle class="circle-container__progress" id="circle-container__progress" r="16" cx="16" cy="16" style="stroke-dashoffset:' . $affichage . '"';
$str .=         'shape-rendering="geometricPrecision">';
$str .=     '</circle>';
$str .= '</svg>';

$str .= '<button id="encherebutton" onclick="encherir(event)"><span>Enchérir</span></button>';

    
$str .= '<div class="temps">';
$str .=    '<p id="dateTitle">'."L'enchère se terminera dans" . '</p>';
$str .=     '<p id="temps">'. $date .'</p>';
$str .= '</div>';

$str .= '<div class="prix">';
$str .= '<div>';
$str .= '<p> Prix de retrait </p>';
$str .=     '<p id="min">' . $prixRetrait . '€</p>';

$str .= '</div>';
$str .= '<div>';

$str .=     '<p>Prix Actuel</p>';
$str .=     '<p id="act">'. $prixact .'€ </p>';

$str .= '</div>';
$str .= '<div>';

$str .=     '<p> Prix de départ </p>';
$str .=     '<p id="max">' .$prixMax .'€</p>';
$str .= '</div>';
$str .= '</div>';
$str .= '<input type="hidden" id="instantDerniereEnchere" name="instantDerniereEnchere" value="' . $instantDerniereEnchere . '">';
$str .= '<input type="hidden" id="instantFin" name="instantFin" value="' . $enchere->getInstantFin()->getTimestamp() . '">';
$str .= '<input type="hidden" id="dateDebut" name="dateDebut" value="' . $enchere->getDateDebut()->getTimestamp() . '">';

echo $str;

?>