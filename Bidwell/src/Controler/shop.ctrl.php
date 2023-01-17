<?php
// Inclusion du framework
use Bidwell\Framework\View;
// Inclusion du modèle
use Bidwell\Model\Utilisateur;

require_once __DIR__.'/../../vendor/autoload.php';

session_start();
$login=$_SESSION["login"];
$myUtilisateur=Utilisateur::read($login);
$jet=$myUtilisateur->getNbJetons();
////////////////////////////////////////////////////////////////////////////
// Construction de la vue
////////////////////////////////////////////////////////////////////////////
$view = new View();

$view->assign("jet",$jet);
//$_SESSION["login"] = "gatou";
// Charge la vue
$view->display("shopjetons.view.php");