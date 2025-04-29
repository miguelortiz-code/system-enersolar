<?php
require_once 'config/headers.php';
require_once 'routes/router.php';
require_once 'config/connection.php';
require_once 'middleware/auth.middleware.php';
require_once 'helpers/env.helper.php';

EnvHelper::load();

$router = new Router();
require_once 'routes/index.routes.php';

$router->run();