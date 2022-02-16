<?php

use App\Models\User;
use App\Models\Posts;
use App\Controllers\PostsController;

$app->get('/', PostsController::class. ':showHome')->setName('home');
$app->get('/posts', PostsController::class. ':index')->setName('posts.index');
$app->get('/veiwPost/{ID}', PostsController::class. ':show')->setName('singlePost.view');


$app->get('/login', function () { //path - auth
    $user = new User;
    var_dump($user);
});

$app->get('/register', function () { //path - auth
    echo 'register';
});

