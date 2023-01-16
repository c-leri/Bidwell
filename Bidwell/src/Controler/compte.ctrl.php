<?php
// Inclusion du framework
use Bidwell\Framework\View;

// Inclusion du modèle
use Bidwell\Model\Utilisateur;

require_once __DIR__.'/../../vendor/autoload.php';

//Récupération des informations de l'utilisateur.
session_start();
$login = $_SESSION["login"];
$utilisateur = Utilisateur::read($login);
$email=$utilisateur->getEmail();
$numtel = $utilisateur->getNumeroTelephone();
$nbJetons = $utilisateur->getNbJetons();
$dateFin = $utilisateur->getTempsRestant();


////////////////////////////////////////////////////////////////////////////
// Construction de la vue
////////////////////////////////////////////////////////////////////////////
$view = new View();

$view->assign('login', $login);
$view->assign('email', $email);
$view->assign('numtel', $numtel);
$view->assign('nbJetons', $nbJetons);
$view->assign('dateFin', $dateFin);
// Charge la vue
$view->display("compte.view.php");