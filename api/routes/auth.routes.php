<?php
require_once 'controllers/auth.controller.php';
require_once 'routes/router.php';

$router->post('/auth/login', function(){
    $controller = new AuthController();
    $controller->validateLogin();
});

$router->post('/auth/register', function(){
    $controller = new AuthController();
    $controller->register();
});

$router->get('/auth/profile', function (){
    $controller = new AuthController();
    $controller->profile();
});
