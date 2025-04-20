<?php

require_once 'controllers/controller.php';
require_once 'routes/router.php';
require_once 'helpers/helper.global.php';

$resources = ['products', 'users', 'categories', 'states'];

foreach ($resources as $resource) {
    // GET /api/resource
    $router->get("/api/$resource", function () use ($resource) {
        $controller = new Controller($resource);
        $controller->index();
    });

    // GET /api/resource/{id}
    $router->get("/api/$resource/{id}", function ($params) use ($resource) {
        $controller = new Controller($resource);
        $controller->show($params['id']);
    });

    // POST /api/resource
    $router->post("/api/$resource", function () use ($resource) {
        $data = json();
        $controller = new Controller($resource);
        $controller->create($data);
    });

    // PUT /api/resource/{id}
    $router->put("/api/$resource/{id}", function ($params) use ($resource) {
        $data = json();
        $controller = new Controller($resource);
        $controller->update($params['id'], $data);
    });

    // DELETE /api/resource/{id}
    $router->delete("/api/$resource/{id}", function ($params) use ($resource) {
        $controller = new Controller($resource);
        $controller->delete($params['id']);
    });
}

// LOGIN DE USUSARIO
$router->post('/api/login', function (){
    $data = json();
    $controller = new Controller('users');
    $controller->login($data['email'], $data['password']);
});



$router->post('/api/register', function (){
    $data = json();
    $controller = new Controller('users');
    $controller->register($data);
});