<?php

require_once './routes/router.php';

$router->get('/products', function () {
    $productController = new productController();
    $products = $productController->getAllProducts();
  
    echo json_encode([
        'success' => true,
        'data' => $products
    ]);
});