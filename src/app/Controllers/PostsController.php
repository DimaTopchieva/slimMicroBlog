<?php

namespace App\Controllers;

use PDO;
use App\Models\Posts;
use Slim\Http\UploadedFile;
use App\Validator\Validator;

class PostsController extends Controller
{
	public function uploadPostImage($postImage, $postID)
	{
		$postImageName = $postImage->getClientFilename();
    	$postImage->moveTo('images/'. $postImageName);
    	
    	$addImageQuery = $this->c->db->prepare("UPDATE Post SET image = :postImageName WHERE ID = :postID");
		
		$addImageQuery->execute([
			'postImageName' => $postImageName,
			'postID' => $postID
		]);
	}
	
	public function index($Request, $Response)
	{		
		$posts = $this->c->db->query("SELECT * FROM Post");
		if ($posts !== FALSE) {
    	$posts=$posts->fetchAll(PDO::FETCH_CLASS, Posts::class);
		}
    	return $this->c->view->render($Response, 'posts/posts.twig', [
    	'posts' => $posts
    	]);
	}
	
	public function showHome($Request, $Response)
	{
		$posts = $this->c->db->query("SELECT * FROM Post ORDER BY created_at DESC LIMIT 5");
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
	
    	return $this->c->view->render($Response, 'posts/singlePost.twig', [
    		'viewPost' => $viewPost
    	]);
	}
	
	public function create($Request, $Response, $args)
	{
		
    	return $this->c->view->render($Response, 'posts/postCreate.twig');
	}
	public function createPost($Request, $Response, $args)
	{
		$title = $Request->getParam('title');
    	$postBody = $Request->getParam('bodyPost'); 
    	
    	$newImage = $Request->getUploadedFiles();
    	$postImage = $newImage['postImage']; 
    	
    	unset($_SESSION["titleError"]);
    	unset($_SESSION["postBodyError"]);
    	
    	$_SESSION["titleError"] = Validator::validatePostTitle($title);
    	$_SESSION["postBodyError"] = Validator::validatePostBody($postBody); 
    	
    	if ($_SESSION["titleError"] == NULL && $_SESSION["postBodyError"] == NULL) {	
    	
    		$insertQuery = $this->c->db->prepare("INSERT INTO Post (title, content, created_by) VALUES (:title, :postBody, :created_by)");
			$insertQuery->execute([
				'title' => $title,
				'postBody' => $postBody,
				'created_by' => $_SESSION['loggedInUser_ID']
			]);
		
    		$newPost = $this->c->db->prepare("SELECT ID FROM Post WHERE title = :title AND content = :postBody");
			$newPost->execute([
				'title' => $title,
				'postBody' => $postBody
			]);
		
			$newPost = $newPost->fetch();
		
			if ($postImage->getError() === UPLOAD_ERR_OK) {
        		$this->uploadPostImage($postImage, $newPost[0]);
    		} 
	
    		return $Response->withRedirect($this->c->router->pathFor('singlePost.view', $newPost));
    	}
    	else return $Response->withRedirect($this->c->router->pathFor('post.create'));
	}
	
	public function showEdit($Request, $Response, $args)
	{
		$editPost = $this->c->db->prepare("SELECT * FROM Post WHERE ID = :ID");
		$editPost->execute([
			'ID' => $args['ID']
		]);
		$editPost = $editPost->fetch(PDO::FETCH_OBJ);
	
    	return $this->c->view->render($Response, 'posts/postEdit.twig', [
    		'editPost' => $editPost
    	]);
	}
	
	public function editPost($Request, $Response, $args)
	{
		$title = $Request->getParam('title');
    	$postBody = $Request->getParam('bodyPost');  
    	$postID = $args['ID'];	
    	
    	$newImage = $Request->getUploadedFiles();
    	$postImage = $newImage['postImage'];
    	
    	unset($_SESSION["titleError"]);
    	unset($_SESSION["postBodyError"]);
    	
    	$_SESSION["titleError"] = Validator::validatePostTitle($title);
    	$_SESSION["postBodyError"] = Validator::validatePostBody($postBody);
    	
    	if ($postImage->getError() === UPLOAD_ERR_OK) {
        	$this->uploadPostImage($postImage, $postID);
    	}
    	
    	if ($_SESSION["titleError"] == NULL && $_SESSION["postBodyError"] == NULL) {
    	
    		$editQuery = $this->c->db->prepare("UPDATE Post SET title = :title, content = :postBody WHERE ID = :postID");
		
			$editQuery->execute([
				'title' => $title,
				'postBody' => $postBody,
				'postID' => $postID
			]);
		
    	return $Response->withRedirect($this->c->router->pathFor('singlePost.view', ['ID' => $postID]));
    	} else return $Response->withRedirect($this->c->router->pathFor('post.edit', ['ID' => $postID]));
	}
	public function deletePost($Request, $Response, $args)
	{
		$deletePost = $this->c->db->prepare("DELETE FROM Post WHERE ID = :ID");
		$deletePost->execute([
			'ID' => $args['ID']
		]);
	
    	return $Response->withRedirect($this->c->router->pathFor('posts.index'));
	}
}




