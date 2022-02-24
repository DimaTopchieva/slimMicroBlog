<?php

namespace App\Auth;

use PDO;
use App\Models\User;

class Auth
{
	protected $c;

	public function __construct($c)
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
	public function loginShow($Request, $Response)
	{
    	return $this->c->view->render($Response, 'auth/login.twig', );
	}
	
	public function loggedUser($Request, $Response, $args)
	{
		$name = $Request->getParam('username');
    	$pass = $Request->getParam('password'); 
    	$hashed_password = password_hash($pass, PASSWORD_DEFAULT);	
    	
    	$query = $this->c->db->prepare("SELECT * FROM Users WHERE Name = :name");	
		$query->execute([
			'name' => $name
			//'password' => $hashed_password  AND password = :password
		]);	
		$foundUser = $query->fetchAll(PDO::FETCH_CLASS, User::class);	
		if (count($foundUser) == 1) {
  			password_verify($pass, $foundUser[0]->password);
  			$_SESSION['loggedInUser_ID'] = $foundUser[0]->ID;
    		return $Response->withRedirect($this->c->router->pathFor('home'));
		}
		else return $Response->withRedirect($this->c->router->pathFor('loginShow'));
	}
	
	public function logoutUser($Request, $Response)
	{
		
		unset($_SESSION['loggedInUser_ID']);
	
    	return $Response->withRedirect($this->c->router->pathFor('home'));
	}
}





	



