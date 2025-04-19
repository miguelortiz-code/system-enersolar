<?php

require_once './routes/router.php';

$router->get('/products', function () {
    $productController = new productController();
    $productController->getAllProducts();
});


$router->get('/products/{id}', function(){
    $id = basename($_SERVER['REQUEST_URI']);
    $productController = new productController();
    $productController->getProductById($id);


});


$router->post('/products', function(){
    $productController = new productController();
    $productController->createdProduct();
});


$router->put('/products/{id}', function($parmas){
    $productController = new productController();
    $productController->updateProduct($parmas);
});


$router->delete('/products/{id}', function($params){
    $productController = new productController();
    $productController->deleteProduct($params);
});