<?php
// Inclusion du framework
use Bidwell\Framework\View;

require_once __DIR__.'/../../vendor/autoload.php';

////////////////////////////////////////////////////////////////////////////
// Construction de la vue
////////////////////////////////////////////////////////////////////////////
$view = new View();
session_start();

// Charge la vue
$view->display("main.view.php");