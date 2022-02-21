<?php

use App\Models\User;
use App\Models\Posts;
use App\Controllers\PostsController;
use App\Controllers\UserController;
use App\Middleware\RedirectToLogin;

$app->get('/', PostsController::class. ':showHome')->setName('home');
$app->get('/posts', PostsController::class. ':index')->setName('posts.index')->add(new RedirectToLogin($container['router']));
$app->get('/veiwPost/{ID}', PostsController::class. ':show')->setName('singlePost.view')->add(new RedirectToLogin($container['router']));
$app->get('/newPost', PostsController::class. ':create')->setName('post.create')->add(new RedirectToLogin($container['router']));
$app->post('/newPost', PostsController::class. ':createPost')->setName('newPost.created');
$app->get('/editPost/{ID}', PostsController::class. ':showEdit')->setName('post.edit')->add(new RedirectToLogin($container['router']));
$app->post('/editPost/{ID}', PostsController::class. ':editPost')->setName('post.editRed');
$app->get('/deletePost/{ID}', PostsController::class. ':deletePost')->setName('post.delete')->add(new RedirectToLogin($container['router']));

$app->get('/register', UserController::class. ':registerShow')->setName('registerShow');
$app->post('/register', UserController::class. ':registerUser')->setName('registerUser');
$app->get('/login', UserController::class. ':loginShow')->setName('loginShow');
$app->post('/login', UserController::class. ':loggedUser')->setName('loggedUser');
$app->get('/editUser', UserController::class. ':editUserShow')->setName('editUserShow')->add(new RedirectToLogin($container['router']));
$app->post('/editUser', UserController::class. ':editUser')->setName('editUser');
$app->get('/privileges', UserController::class. ':indexAdmin')->setName('privileges')->add(new RedirectToLogin($container['router']));
$app->get('/delete', UserController::class. ':deleteUser')->setName('deleteUser')->add(new RedirectToLogin($container['router']));
$app->get('/logout', UserController::class. ':logoutUser')->setName('logoutUser')->add(new RedirectToLogin($container['router']));
$app->get('/editUserRole/{ID}', UserController::class. ':giveAdminRole')->setName('giveAdminRole')->add(new RedirectToLogin($container['router']));
$app->get('/editAdminRole/{ID}', UserController::class. ':giveUserRole')->setName('giveUserRole')->add(new RedirectToLogin($container['router']));

