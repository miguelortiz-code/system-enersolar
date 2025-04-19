<?php

require_once './routes/router.php';

$router->get('/users', function(){
    $userController = new userController();
    $users = $userController->getAllUsers();

    echo json_encode([
        'success' => true,
        'total' => count($users),
        'data' => $users
    ]);
});