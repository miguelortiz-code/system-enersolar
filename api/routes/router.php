<?php
class Router {
    private $routes = [];

    public function get($path, $controller) {
        $this->addRoute('GET', $path, $controller);
    }

    public function post($path, $controller) {
        $this->addRoute('POST', $path, $controller);
    }

    public function put($path, $controller) {
        $this->addRoute('PUT', $path, $controller);
    }

    public function delete($path, $controller) {
        $this->addRoute('DELETE', $path, $controller);
    }

    private function addRoute($method, $path, $controller) {
        $regex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_-]+)', $path);
        $regex = '#^' . $regex . '$#';
        $this->routes[] = [
            'method' => $method,
            'path' => $regex,
            'controller' => $controller
        ];
    }

    public function run() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && preg_match($route['path'], $requestUri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                call_user_func($route['controller'], $params);
                return;
            }
        }

        http_response_code(404);
        echo json_encode([
            'error' => true,
            'message' => 'La ruta solicitada no se encuentra en el servidor.' . $requestUri
            ]);
    }
}

$router = new Router();
require_once 'routes/routes.php';
$router->run();