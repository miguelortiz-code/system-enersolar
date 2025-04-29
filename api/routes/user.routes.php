<?php
require_once 'controllers/user.controller.php';
require_once 'routes/router.php';

$router->get('/api/users/{id}', function($id){
    $controller = new UserController();
    $controller->getUserById($id);
});

// Ruta GET con lógica condicional para filtrar
$router->get('/api/users', function(){
    $controller = new UserController();
    // Verificar si hay filtros en la consulta
    if (!empty($_GET)) {
        $controller->getUsersByFilters();
    } else {
        $controller->getAllUsers();
    }
});


$router->post('/api/users', function(){
    $controller = new UserController();
    $controller->createUser();
});

$router->put('/api/users/{id}', function($id){
    $controller = new UserController();
    $controller->updateUser($id);
});

$router->delete('/api/users/{id}', function($id){
    $controller = new UserController();
    $controller->deleteUser($id);
});