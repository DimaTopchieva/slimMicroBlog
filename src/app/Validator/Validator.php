<?php

namespace App\Validator;

class Validator
{	
	public function validateUsername($data)
	{
		$data = trim($data);
		if (strpos($data, ' ')) {
  			$_SESSION["usernameError"] = "Username should not contain whitespaces";
		}
		if (strlen($data) == 0) {
  			$_SESSION["usernameError"] = "Username is required";
		}
		if (strlen($data) > 255) {
  			$_SESSION["usernameError"] = "Username should not be more than 255 symbols";
		}
		
		return $_SESSION["usernameError"];
	}
	
	public function validateEmail($data)
	{
		$data = trim($data);
		//pattern, unique
		if (strpos($data, ' ')) {
  			$_SESSION["emailError"] = "Email should not contain whitespaces";
		}
		if (strlen($data) == 0) {
  			$_SESSION["emailError"] = "Email is required";
		}
		if (strlen($data) > 255) {
  			$_SESSION["emailError"] = "Email should not be more than 255 symbols";
		}
		
		return $_SESSION["emailError"];
	}
	
	public function validatePassword($data)
	{
		$data = trim($data);
		if (strpos($data, ' ')) {
  			$_SESSION["passError"] = "Password should not contain whitespaces";
		}
		if (strlen($data) == 0) {
  			$_SESSION["passError"] = "Password is required";
		}
		if (strlen($data) > 255) {
  			$_SESSION["passError"] = "Password should not be more than 255 symbols";
		}
		if (strlen($data) < 8) {
  			$_SESSION["passError"] = "Password should be at least 8 symbols";
		}
		
		return $_SESSION["passError"];
	}
	
	public function validatePostTitle($data)
	{
		$data = trim($data);
		if (strlen($data) == 0) {
  			$_SESSION["titleError"] = "Title is required";
		}
		if (strlen($data) > 255) {
  			$_SESSION["titleError"] = "Title should not be more than 255 symbols";
		}
		
		return $_SESSION["titleError"];
	}
	
	public function validatePostBody($data)
	{
		$data = trim($data);
		if (strlen($data) == 0) {
  			$_SESSION["postBodyError"] = "Content is required";
		}
		
		return $_SESSION["postBodyError"];
	}
}





	



