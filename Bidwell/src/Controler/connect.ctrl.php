<?php

// Inclusion du framework
use Bidwell\Framework\View;

require_once __DIR__.'/../../vendor/autoload.php';

// Récupérations des données du formulaire
$login=$_POST['login'] ?? '';
$password = $_POST['password'] ?? '';

// Ouverture de la session
session_start();

// Si la session connait le login alors on s'est déjà connecté, on va sur la page principale
if(isset($_SESSION['login'])){
// Recupère le login pour la vue
$login=$_SESSION['login'];
// Charge la page de l'application et termine
$view = new View();
$view->display("main.view.php");
}else{
    $view = new View();
    $view->display("connect.view.php");
}