<?php

namespace App\Models;

class User
{
	public $ID;
    public $name;
    public $email;
    public $password;
   
    public function __construct() {      
        $this->ID = $this->ID;
    	$this->name = $this->name;
    	$this->email = $this->email;
    	$this->password = $this->password;
    }
}