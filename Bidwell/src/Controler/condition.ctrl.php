<?php
namespace Bidwell\Bidwell\Controler;

// Inclusion du framework
use Bidwell\Bidwell\Framework\View;

require_once __DIR__.'/../../vendor/autoload.php';

////////////////////////////////////////////////////////////////////////////
// Construction de la vue
////////////////////////////////////////////////////////////////////////////
$view = new View();

// Charge la vue
$view->display("condition.view.php");