<?php
// Inclusion du framework
use Bidwell\Framework\View;

require_once __DIR__.'/../../vendor/autoload.php';

// On passe l'utilisateur en https si il est connecté sur le serveur en http (le https ne marcherait pas sur un serveur en localhost par exemple)
// pour que les mots de passes ne passent pas en clair sur le réseau
if(isset($_SERVER["HTTPS"]) && isset($_SERVER['SERVER_NAME']) && $_SERVER["HTTPS"] != "on" && $_SERVER['SERVER_NAME'] === '192.168.14.212')
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

////////////////////////////////////////////////////////////////////////////
// Construction de la vue
////////////////////////////////////////////////////////////////////////////
$view = new View();

$view->assign('connected', false);

// Charge la vue
$view->display("signup.view.php");