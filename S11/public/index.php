<?php

require_once "../core/Router.php";
require_once "../config/app.php";

$router = new Router();

// Đăng ký routes
$router->get('/products', 'ProductController@index');
$router->get('/products/create', 'ProductController@create');
$router->post('/products/create', 'ProductController@store');

// Lấy URL hiện tại
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Chạy router
$router->dispatch($uri, $method);