<?php

use App\{Redirect, View};
use App\ViewVariables\{AuthViewVariables, ErrorViewVariables, ViewVariables};
use Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once 'vendor/autoload.php';
session_start();

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
$loader = new FilesystemLoader('views');
$twig = new Environment($loader);

$authVariables = [
    AuthViewVariables::class,
    ErrorViewVariables::class
];
/** @var ViewVariables $variable */
foreach ($authVariables as $variable) {
    $variable = new $variable;
    $twig->addGlobal($variable->getName(), $variable->getValue());
}

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $route) {
    $route->addRoute('GET', '/', ['App\Controllers\StockController', 'index']);
    $route->addRoute('GET', '/register', ['App\Controllers\RegisterController', 'index']);
    $route->addRoute('POST', '/register', ['App\Controllers\RegisterController', 'register']);
    $route->addRoute('GET', '/login', ['App\Controllers\LoginController', 'index']);
    $route->addRoute('POST', '/login', ['App\Controllers\LoginController', 'login']);
    $route->addRoute('GET', '/logout', ['App\Controllers\LoginController', 'logout']);
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo "Page not found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo "Method not allowed";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        [$controller, $method] = $handler;

        $response = (new $controller)->{$method}($vars);
        if ($response instanceof View) {
            echo $twig->render($response->getTemplatePath() . '.twig', $response->getProperties());
            unset($_SESSION['errors']);
        }

        if ($response instanceof Redirect) {
            header('Location: ' . $response->getUrl());
        }
        break;
}