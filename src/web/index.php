<?php
// public/index.php
require_once __DIR__ . '/../web/vendor/autoload.php';

require_once __DIR__ . '/../core/Router.php';

session_start();

$router = new Router();

// Define routes

$router->add('/', [
    'controller' => 'LoginController',
    'method' => 'index'
]);

$router->add('/login', [
    'controller' => 'LoginController',
    'method' => 'index'
]);

$router->add('/login/login_user', [
    'controller' => 'LoginController',
    'method' => 'login_user'
]);

$router->add('/login/logout', [
    'controller' => 'LoginController',
    'method' => 'logout_user'
]);

$router->add('/register', [
    'controller' => 'RegisterController',
    'method' => 'index'
]);

$router->add('/register/add_user', [
    'controller' => 'RegisterController',
    'method' => 'add_user'
]);

$router->add('/gallery', [
    'controller' => 'GalleryController',
    'method' => 'index'
]);

$router->add('/gallery/upload', [
    'controller' => 'GalleryController',
    'method' => 'upload'
]);

$router->add('/gallery/add_image_to_favorites', [
    'controller' => 'GalleryController',
    'method' => 'update_favourite_images'
]);

$router->add('/debug', [
    'controller' => 'DebugController',
    'method' => 'index'
]);

// Process the current request
$requestUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($requestUrl);