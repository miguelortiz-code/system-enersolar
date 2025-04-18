<?php 
    require_once 'config/headers.php';
    require_once 'config/connection.php';
    require_once 'controllers/index.controller.php';
    require_once 'routes/router.php';

    $router->run();
?>