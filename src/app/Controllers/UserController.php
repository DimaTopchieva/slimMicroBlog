<?php

namespace App\Controllers;

use PDO;
use App\Models\User;
use App\Auth\Auth;
use App\Validator\Validator;

class UserController extends Controller
{
	public function registerShow($Request, $Response, $args)
	{
    	return $this->c->view->render($Response, 'users/register.twig');
	}
	
	public function registerUser($Request, $Response, $args)
	{
		$name = $Request->getParam('username');
    	$email = $Request->getParam('email'); 
    	$pass = $Request->getParam('password');
    	$cpass = $Request->getParam('cpassword'); 
    	
    	unset($_SESSION["usernameError"]);
    	unset($_SESSION["emailError"]);
    	unset($_SESSION["passError"]);
    	
    	$_SESSION["usernameError"] = Validator::validateUsername($name);
    	$_SESSION["emailError"] = Validator::validateEmail($email);
    	$_SESSION["passError"] = Validator::validatePassword($pass);
    	
    	if ($pass === $cpass && $_SESSION["usernameError"] == NULL && $_SESSION["emailError"] == NULL && $_SESSION["passError"] == NULL) {
  			$hashed_password = password_hash($pass, PASSWORD_DEFAULT);
  			$insertQuery = $this->c->db->prepare("INSERT INTO Users (Name, email, password, role_id) VALUES (:name, :email, :pass, 1)");
			$insertQuery->execute([
				'name' => $name,
				'email' => $email,
				'pass' => $hashed_password
			]);
			return $Response->withRedirect($this->c->router->pathFor('loginShow'));
		}  else {
    		return $Response->withRedirect($this->c->router->pathFor('registerShow'));
    	}
	}
	
	public function editUserShow($Request, $Response, $args)
	{
    	return $this->c->view->render($Response, 'users/userEdit.twig');
	}
	
	public function editUser($Request, $Response, $args)
	{	
		$name = $Request->getParam('username');
    	$email = $Request->getParam('email'); 
    	$pass = $Request->getParam('password');
    	$cpass = $Request->getParam('cpassword'); 
    	
    	unset($_SESSION["usernameError"]);
    	unset($_SESSION["emailError"]);
    	unset($_SESSION["passError"]);
    	
    	$_SESSION["usernameError"] = Validator::validateUsername($name);
    	$_SESSION["emailError"] = Validator::validateEmail($email);
    	$_SESSION["passError"] = Validator::validatePassword($pass);
    	
    	if ($pass === $cpass && $_SESSION["usernameError"] == NULL && $_SESSION["emailError"] == NULL && $_SESSION["passError"] == NULL) {
  			$hashed_password = password_hash($pass, PASSWORD_DEFAULT);
  			$editQuery = $this->c->db->prepare("UPDATE Users SET Name = :name, email = :email, password = :password WHERE ID = :usertID");
			$editQuery->execute([
				'name' => $name,
				'email' => $email,
				'password' => $hashed_password,
				'usertID' => $_SESSION['loggedInUser_ID']
			]);	
			return $Response->withRedirect($this->c->router->pathFor('home'));
		} else
			return $Response->withRedirect($this->c->router->pathFor('editUserShow'));
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
    	return $this->c->view->render($Response, 'users/adminEdit.twig', [
    		'users' => $users,
    		'usersAdmins' => $usersAdmins
    	]);
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





	



