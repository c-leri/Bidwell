<?php
// Inclusion du framework
use Bidwell\Framework\View;

require_once __DIR__.'/../../vendor/autoload.php';

session_start();
$view = new View();
if (isset($_SESSION['login'])){
    $login = $_SESSION['login'];
    $view->assign('connected', true);
} else {
    $login = "";
    $view->assign('connected', false);
}

session_write_close();

////////////////////////////////////////////////////////////////////////////
// Construction de la vue
////////////////////////////////////////////////////////////////////////////


$view->assign('login', $login);

// Charge la vue
$view->display("main.view.php");