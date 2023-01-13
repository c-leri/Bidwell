<?php
namespace Bidwell\Bidwell\Controler;

// Ouverture de la session
session_start();

// Fermeture
session_destroy();

// Retourne a la page principale
header('Location: main.ctrl.php');