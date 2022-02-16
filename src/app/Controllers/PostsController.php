<?php

namespace App\Controllers;

use PDO;
use App\Models\Posts;

class PostsController extends Controller
{
	public function index($Request, $Response)
	{
		$posts = $this->c->db->query("SELECT * FROM Post");
		if ($posts !== FALSE) {
    	$posts=$posts->fetchAll(PDO::FETCH_CLASS, Posts::class);
		}
    	return $this->c->view->render($Response, 'posts.twig', [
    	'posts' => $posts
    	]);
	}
	
	public function showHome($Request, $Response)
	{
		$posts = $this->c->db->query("SELECT * FROM Post");
		if ($posts !== FALSE) {
    	$posts=$posts->fetchAll(PDO::FETCH_CLASS, Posts::class);
		} 
		
    	return $this->c->view->render($Response, 'home.twig', [
    		'posts' => $posts
    	]);
	}
	
	public function show($Request, $Response, $args)
	{
		$viewPost = $this->c->db->prepare("SELECT * FROM Post WHERE ID = :ID");
		$viewPost->execute([
			'ID' => $args['ID']
		]);
		$viewPost = $viewPost->fetch(PDO::FETCH_OBJ);
	
    	return $this->c->view->render($Response, 'singlePost.twig', [
    		'viewPost' => $viewPost
    	]);
	}
}




