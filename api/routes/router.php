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
        // Convert {param} to (?P<param>...)
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
                
                // Verificar si el controlador es una clase y necesita ser instanciada
                if (is_array($route['controller'])) {
                    $controllerClass = $route['controller'][0];
                    $controllerMethod = $route['controller'][1];
                    $controller = new $controllerClass();
                    call_user_func([$controller, $controllerMethod], $params);
                } else {
                    // Si es una función global
                    call_user_func_array($route['controller'], array_values($params));
                }

                return;
            }
        }

        http_response_code(404);
        echo json_encode([
            'error' => true,
            'message' => 'La ruta solicitada no se encuentra en el servidor' . $requestUri
        ]);
    }
}

$router = new Router();