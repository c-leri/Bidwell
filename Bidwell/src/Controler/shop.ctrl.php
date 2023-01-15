<?php
// Inclusion du framework
use Bidwell\Framework\View;
// Inclusion du modèle
use Bidwell\Model\Utilisateur;

require_once __DIR__.'/../../vendor/autoload.php';

////////////////////////////////////////////////////////////////////////////
// Construction de la vue
////////////////////////////////////////////////////////////////////////////
$view = new View();
//session_start();
//$_SESSION["login"] = "gatou";
// Charge la vue
$view->display("shopjetons.view.php");