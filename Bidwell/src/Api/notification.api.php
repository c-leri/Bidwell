<?php
namespace Bidwell\Bidwell\Api;

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

while (true) {
    $out['prix'] = 0;

    echo "data: {

    }";

    ob_end_flush();
    flush();

    if (connection_aborted())
        exit();
}