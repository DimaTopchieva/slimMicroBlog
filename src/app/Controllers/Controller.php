<?php

namespace App\Controllers;

use Slim\Container;

abstract class Controller
{
	protected $c;

	public function __construct(Container $c)
	{
		$this->c = $c;
	}
}