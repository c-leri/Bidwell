<?php

use Bidwell\Framework\View;
// Inclusion du modèle
use Bidwell\Model\Utilisateur;

require_once __DIR__.'/../../vendor/autoload.php';

//On s'informe sur l'utilisateur actuel
session_start();
$login=$_SESSION["login"];
$myUtilisateur=Utilisateur::read($login);
//On lui rajoute ses jetons.
$myUtilisateur->addJetons($_GET['valeur']);
$myUtilisateur->update();
echo $myUtilisateur->getNbJetons();
