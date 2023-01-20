<?php

// Inclusion du framework
use Bidwell\Framework\View;

require_once __DIR__ . '/../../vendor/autoload.php';

// On passe l'utilisateur en https si il est connecté sur le serveur en http (le https ne marcherait pas sur un serveur en localhost par exemple)
// pour que les mots de passes ne passent pas en clair sur le réseau
if(isset($_SERVER["HTTPS"]) && isset($_SERVER['SERVER_NAME']) && $_SERVER["HTTPS"] != "on" && $_SERVER['SERVER_NAME'] == '192.168.14.212')
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

// Récupérations des données du formulaire
$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';

// Ouverture de la session
session_start();

// Si la session connait le login alors on s'est déjà connecté, on va sur la page principale
if (isset($_SESSION['login'])) {
    // Recupère le login pour la vue
    $login = $_SESSION['login'];
    // Charge la page de l'application et termine
    $view = new View();
    $view->assign('connected', true);
    $view->display("main.view.php");
} else {
    $view = new View();
    $view->assign('connected', false);
    $view->display("connect.view.php");
}