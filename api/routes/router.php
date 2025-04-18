<?php
class Router{
    private $routes = [];
    public function get($path, $controller){
        $this->addRoute('GET', $path, $controller);
    }

    public function post($path, $controller){
        $this->addRoute('POST', $path, $controller);
    }

    public function put($path, $controller){
        $this->addRoute('PUT', $path, $controller);
    }

    public function delete($path, $controller){
        $this->addRoute('DELETE', $path, $controller);
    }

    private function addRoute($method, $path, $controller){
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller
        ];
    }

    public function run(){
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];
        foreach($this->routes as $route){
            if($route['method'] === $requestMethod && $route['path'] === $requestUri){
                call_user_func($route['controller']);
                return;
            }
        }

        // Ruta no encontrada
        http_response_code(404);
        echo 'Error: Ruta no encontrada';
    }
}

$router  = new Router();
require_once 'routes/products/product.router.php';
require_once 'routes/categories/category.router.php';