<?php

namespace App\Middleware;

use Slim\Container;
use Slim\Router;

class RedirectToLogin
{
	protected $router;

	public function __construct(Router $router)
	{
		$this->router = $router;
	}
	
	public function __invoke($Request, $Response, $next)
	{
		if (!isset($_SESSION['loggedInUser_ID'])){
			$Response = $Response->withRedirect($this->router->pathFor('loginShow'));
		}
		
		return $next($Request, $Response);
	}
}