<?php

use MiladRahimi\PhpRouter\Router;
use MiladRahimi\PhpRouter\View\View;
use Src\Controllers\LoginController;
use Src\Controllers\UsersController;
use Src\Middlewares\ApiMiddleware;
use Src\Middlewares\AuthMiddleware;

$router = Router::create();
$router->setupView(__DIR__ . '/../views');
$router->pattern('id', '[0-9]+');


$router->get('/', function (View $view) {
    return $view->make('docs');
});

$router->group(['middleware' => [ApiMiddleware::class], 'prefix' => '/api'], function (Router $router) {
    $router->post('/login', [LoginController::class, 'login']);
    $router->post('/user/register', [LoginController::class, 'register']);
    $router->get('/user/get/{id}', [UsersController::class, 'show']);
//    $router->group(['middleware' => [AuthMiddleware::class]], function (Router $router) {
//        $router->get('/post/feed', function () {
//            return "The content of post";
//        });
//    });
});




