<?php
use Bidwell\Server\WebSocketServer;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$server = new WebSocketServer();
$server->start(8080);