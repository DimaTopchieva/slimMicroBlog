<?php

namespace App\Auth;

use PDO;
use App\Models\User;
use Slim\Container;

class Auth
{
	protected $c;

	public function __construct(Container $c)
	{
		$this->c = $c;
	}
	
	public function checkIfLoggedIn()
	{
		return isset($_SESSION['loggedInUser_ID']);
	}
	
	public function checkUserLoggedIn()
	{
		$loggedInUser = $this->c->db->prepare("SELECT * FROM Users WHERE ID = :ID");
		$loggedInUser->execute([
			'ID' => $_SESSION['loggedInUser_ID']
		]);
		$loggedInUser = $loggedInUser->fetch(PDO::FETCH_OBJ);
	
    	return $loggedInUser;
	}
}





	



