<?php

require_once './routes/router.php';

$router->get('/categories', function(){
    $categoryController = new categoryController();
    $categories = $categoryController->getAllCategories();

    echo json_encode([
        'success' => true,
        'total' => count($categories),
        'data' => $categories
    ]);
});