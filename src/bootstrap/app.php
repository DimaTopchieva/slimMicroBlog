<?php

session_start();

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App([
	'settings' => [
		'displayErrorDetails' => true,
	]
]);

$container = $app->getContainer();

$container['db'] = function() {
	return new PDO('mysql:host=localhost;dbname=slimTest', 'root','!123Pass');
};

$container['auth'] = function ($container) {
	return new \App\Auth\Auth($container);  
};

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => false
    ]);

    // Instantiate and add Slim specific extension
    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));
    
    $view->getEnvironment()->addGlobal('auth', [
    	'checkIfLoggedIn' => $container->auth->checkIfLoggedIn(),
    	'checkUserLoggedIn' => $container->auth->checkUserLoggedIn()
    ]);

    return $view;
};

require __DIR__ . '/../routes/web.php';

