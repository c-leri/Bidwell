<?php
include_once(__DIR__."/../framework/View.class.php");

// Ouverture de la session
session_start();

// Fermeture
session_destroy();

$view = new View();

// Retourne a la page principale
header('Location: main.ctrl.php');

?>