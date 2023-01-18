<?php
// Inclusion du framework
use Bidwell\Framework\View;

require_once __DIR__.'/../../vendor/autoload.php';

session_start();
$connected = isset($_SESSION['login']);
session_write_close();
////////////////////////////////////////////////////////////////////////////
// Construction de la vue
////////////////////////////////////////////////////////////////////////////
$view = new View();

$view->assign('connected', $connected);
// Charge la vue
$view->display("a-propos.view.php");