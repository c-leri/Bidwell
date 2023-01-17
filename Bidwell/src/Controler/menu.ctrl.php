<?php
// Inclusion du framework
use Bidwell\Framework\View;

require_once __DIR__.'/../../vendor/autoload.php';

session_start();
$connected = isset($_SESSION['login']);

////////////////////////////////////////////////////////////////////////////
// Construction de la vue
////////////////////////////////////////////////////////////////////////////
$view = new View();

$view->assign('connected', $connected);

// Charge la vue
$view->display("menu.viewpart.php");