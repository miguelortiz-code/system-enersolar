<?php
require_once 'controllers/product.controller.php';
require_once 'routes/router.php';


$router->get('/api/products/{id}', function($id){
    $controller = new ProductController();
    $controller->getProductById($id);
});

$router->get('/api/products', function(){
    $controller = new ProductController();
    if(!empty($_GET)){
        $controller->getProductsByFilters();
    }else{
        $controller->getAllProducts();
    }
});

$router->post('/api/products', function(){
    $controller = new ProductController();
    $controller->createProduct();
});


$router->put('/api/products/{id}', function($id){
    $controller = new ProductController();
    $controller->updateProduct($id);
});


$router->delete('/api/products/{id}', function($id){
    $controller = new ProductController();
    $controller->deleteProduct($id);
});