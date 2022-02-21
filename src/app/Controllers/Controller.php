<?php

namespace App\Controllers;

use Slim\Container;
use App\Models\User;

abstract class Controller
{
	protected $c;

	public function __construct(Container $c)
	{
		$this->c = $c;
	}
}