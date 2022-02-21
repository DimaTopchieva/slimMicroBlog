<?php

namespace App\Controllers;

use PDO;
use App\Models\User;
use App\Auth\Auth;

class UserController extends Controller
{
	public function registerShow($Request, $Response, $args)
	{
    	return $this->c->view->render($Response, 'auth/register.twig');
	}
	
	public function registerUser($Request, $Response, $args)
	{
		$name = $Request->getParam('username');
    	$email = $Request->getParam('email'); 
    	$pass = $Request->getParam('password');
    	$cpass = $Request->getParam('cpassword'); 
    	
    	if ($pass === $cpass) {
  			$insertQuery = $this->c->db->prepare("INSERT INTO Users (Name, email, password, role_id) VALUES (:name, :email, :pass, 1)");
			$insertQuery->execute([
				'name' => $name,
				'email' => $email,
				'pass' => $pass
			]);
		}  		
    	return $Response->withRedirect($this->c->router->pathFor('home'));
	}
	
	public function loginShow($Request, $Response)
	{
    	return $this->c->view->render($Response, 'auth/login.twig', );
	}
	
	public function loggedUser($Request, $Response, $args)
	{
		$name = $Request->getParam('username');
    	$pass = $Request->getParam('password'); 	
    	$query = $this->c->db->prepare("SELECT * FROM Users WHERE Name = :name AND password = :password");	
		$query->execute([
			'name' => $name,
			'password' => $pass
		]);	
		$foundUser = $query->fetchAll(PDO::FETCH_CLASS, User::class);	
		if (count($foundUser) == 1) {
  			$_SESSION['loggedInUser_ID'] = $foundUser[0]->ID;
  			
    		return $Response->withRedirect($this->c->router->pathFor('home'));
		}
	}
	
	public function editUserShow($Request, $Response, $args)
	{
    	return $this->c->view->render($Response, 'auth/userEdit.twig');
	}
	
	public function editUser($Request, $Response, $args)
	{	
		$name = $Request->getParam('username');
    	$email = $Request->getParam('email'); 
    	$pass = $Request->getParam('password');
    	$cpass = $Request->getParam('cpassword'); 
    	
    	if ($pass === $cpass) {
  			$editQuery = $this->c->db->prepare("UPDATE Users SET Name = :name, email = :email, password = :password WHERE ID = :usertID");
			$editQuery->execute([
				'name' => $name,
				'email' => $email,
				'password' => $pass,
				'usertID' => $_SESSION['loggedInUser_ID']
			]);	
		}  		
    	return $Response->withRedirect($this->c->router->pathFor('home'));
	}
	
	public function indexAdmin($Request, $Response)
	{
		$users = $this->c->db->query("SELECT * FROM Users WHERE role_id = 1");
		if ($users !== FALSE) {
    		$users=$users->fetchAll(PDO::FETCH_CLASS, User::class);
		}
		$usersAdmins = $this->c->db->query("SELECT * FROM Users WHERE role_id = 2");
		if ($usersAdmins !== FALSE) {
    		$usersAdmins=$usersAdmins->fetchAll(PDO::FETCH_CLASS, User::class);
		}
    	return $this->c->view->render($Response, 'auth/adminEdit.twig', [
    		'users' => $users,
    		'usersAdmins' => $usersAdmins
    	]);
	}
	
	public function logoutUser($Request, $Response)
	{
		
		unset($_SESSION['loggedInUser_ID']);
	
    	return $Response->withRedirect($this->c->router->pathFor('home'));
	}
	
	public function deleteUser($Request, $Response)
	{
		$deleteUser = $this->c->db->prepare("DELETE FROM Users WHERE ID = :ID");
		$deleteUser->execute([
			'ID' => $_SESSION['loggedInUser_ID']
		]);
    	
    	unset($_SESSION['loggedInUser_ID']);
    	
    	return $Response->withRedirect($this->c->router->pathFor('home'));
	}
	
	public function giveAdminRole($Request, $Response, $args)
	{
		$giveAdminRole = $this->c->db->prepare("UPDATE Users SET role_id = 2 WHERE ID = :ID");
		
		$giveAdminRole->execute([
			'ID' => $args['ID']
		]);
    	
    	return $Response->withRedirect($this->c->router->pathFor('privileges'));
	}
	public function giveUserRole($Request, $Response, $args)
	{
		$giveUserRole = $this->c->db->prepare("UPDATE Users SET role_id = 1 WHERE ID = :ID");
		$giveUserRole->execute([
			'ID' => $args['ID']
		]);
    	
    	return $Response->withRedirect($this->c->router->pathFor('privileges'));
	}
}





	



