<?php
require_once 'controllers/category.controller.php';
require_once 'routes/router.php';

$router->get('/api/categories', function(){
    $controller = new CategoryController();
    $controller->getAllCategories();
});

$router->get('/api/categories', function(){
    $controller = new CategoryController();
    $controller->getAllCategories();
});

$router->post('/api/categories', function(){
    $controller = new CategoryController();
    $controller->createCategory();
});

$router->put('/api/categories/{id}', function($id){
    $controller = new CategoryController();
    $controller->updateCategory($id);
});


$router->delete('/api/categories/{id}', function($id){
    $controller = new CategoryController();
    $controller->deleteCategory($id);
});
