<?php

require "../bootstrap.php";

use Src\Controller\SubscriberController;
use Src\Controller\FieldController;
use Src\Controller\FieldSubscriberController;

use Src\System\RequestProcessor;

$request = new RequestProcessor($dbConnection);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

$id = (isset($uri[2])) ? (int)$uri[2] : null;

$requestMethod = $_SERVER["REQUEST_METHOD"];

switch ($request->getRoute()) {
    case 'subscriber':
        $controller = new SubscriberController($dbConnection, $request->getRequestMethod(), $request->getParam());
        break;
    case 'field':
        $controller = new FieldController($dbConnection, $request->getRequestMethod(), $request->getParam());
        break;
    case 'field_subscriber':
        $controller = new FieldSubscriberController($dbConnection, $request->getRequestMethod(), $request->getParam());
        break;
    default:
        header("HTTP/1.1 404 Not Found");
        exit();
        break;
}

$controller->processRequest($controller);
