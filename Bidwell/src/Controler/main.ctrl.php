<?php
// Inclusion du framework
use Bidwell\Framework\View;

require_once __DIR__.'/../../vendor/autoload.php';

session_start();
if (isset($_SESSION['login'])){
    $login = $_SESSION['login'];
} else {
    $login = "";
}

session_write_close();

////////////////////////////////////////////////////////////////////////////
// Construction de la vue
////////////////////////////////////////////////////////////////////////////
$view = new View();

$view->assign('login', $login);

// Charge la vue
$view->display("main.view.php");