<?php
use Bidwell\Model\Enchere;

require_once __DIR__.'/../../vendor/autoload.php';


$enchere = Enchere::read($_GET['id']);

$prixRetrait = $_GET['prixRetrait'];
$prixMax = $_GET['prixHaut'];
$prixact = $prixMax;

$pourcent = (1 - ($prixact - $prixfin) / ($prixdep - $prixfin) ) * 74;
$affichage = round($pourcent, 1, PHP_ROUND_HALF_DOWN);

$maintenant = new DateTime();
$tempsRes = (int)$maintenant->format('Uv') - (int)$enchere->getInstantFin()->format('Uv');


$str = '';

$str .= 'svg class="circle-container" viewBox="2 -2 28 36">';
$str .=     '<linearGradient id="gradient">';
$str .=         '<stop class="stop1" offset="0%" />';
$str .=         '<stop class="stop2" offset="100%" />';
$str .=     '</linearGradient>';
$str .=     '<circle class="circle-container__background" r="16" cx="16" cy="16">';
$str .=     '</circle>';
$str .=     '<circle class="circle-container__progress" r="16" cx="16" cy="16" style="stroke-dashoffset:' . $affichage . '"';
$str .=         'shape-rendering="geometricPrecision">';
$str .=     '</circle>';
$str .= '</svg>';

$str .= '<button id="encherebutton" onclick="encherir(event)"><span>Enchérir</span></button>';

    
$str .= '<div class="temps">';
$str .=    '<p> Temps Restant </p>';
$str .=     '<p id="temps">'. $tempsRes .'</p>';
$str .= '</div>';

$str .= '<div class="prix">';
$str .= '<div>';
$str .= '<p> Prix de retrait </p>';
$str .=     '<p id="min">' . $prixfin . '€</p>';

$str .= '</div>';
$str .= '<div>';

$str .=     '<p>Prix Actuel</p>';
$str .=     '<p id="act">'. $prixact .'€ </p>';

$str .= '</div>';
$str .= '<div>';

$str .=     '<p> Prix de départ </p>';
$str .=     '<p id="max">' .$prixdep .'€</p>';
$str .= '</div>';
$str .= '</div>';

echo $str;

?>