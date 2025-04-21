<?php

require_once 'controllers/user.controller.php';
require_once 'routes/router.php';

// Obtener todos los usuarios
$router->get('/users', function () {
    $controller = new userController();
    if (!empty($_GET)) {
        $controller->filterUsers();
    } else {
        $controller->index();
    }
});

// Obtener un usuario por ID
$router->get('/user/{id}', function ($params) {
    $controller = new userController();
    $controller->show($params);
});

// Crear un nuevo usuario
$router->post('/user', function () {
    $controller = new userController();
    $controller->createUser();
});

// Actualizar un usuario por ID
$router->put('/user/{id}', function ($params) {
    $controller = new userController();
    $controller->updateUser($params);
});

// Eliminar un usuario por ID
$router->delete('/user/{id}', function ($params) {
    $controller = new userController();
    $controller->deleteUser($params);
});

// Login de usuario
$router->post('/login', function () {
    $controller = new userController();
    $controller->loginUser();
});

// register de usuario
$router->post('/register', function () {
    $controller = new userController();
    $controller->createUser();
});