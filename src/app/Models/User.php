<?php

namespace App\Models;

class User
{
	public $ID;
    public $Name;
    public $email;
    public $password;
    public $role_id;
   
    public function __construct() {      
        $this->ID = $this->ID;
    	$this->Name = $this->Name;
    	$this->email = $this->email;
    	$this->password = $this->password;
    	$this->role_id = $this->role_id;
    }
}