<?php

namespace App\Models;

class Posts
{
    public $ID;
    public $title;
    public $content;
    public $image;
    public $created_by;
    public $created_At;
   
    public function __construct() {      
        $this->ID = $this->ID;
    	$this->title = $this->title;
    	$this->content = $this->content;
    	$this->image = $this->image;
    	$this->created_At = $this->created_At;
    }
}