<?php

require_once 'models/product.model.php';
require_once 'helpers/response.php';
require_once 'middleware/auth.middleware.php';

class ProductController
{
    private $model;

    public function __construct()
    {
        $this->model = new ProductModel();
    }

    // ==========================================
    // OBTENER UN PRODUCTO POR ID
    // ==========================================
    public function getProductById($id)
    {
        AuthMiddleware::handle();

        if (!$id) {
            Response::error('ID del producto no proporcionado', 400);
            return;
        }

        // Verificar si el producto existe
        if (!$this->checkProductExists($id)) {
            return;
        }

        $product = $this->model->getProductById($id);
        Response::success([
            'message' => 'Producto encontrado',
            'data' => $product
        ]);
    }

    // ==========================================
    // OBTENER TODOS LOS PRODUCTOS
    // ==========================================
    public function getAllProducts()
    {
        AuthMiddleware::handle();

        try {
            $products = $this->model->getAllProducts();
            Response::success([
                'message' => 'Productos obtenidos correctamente',
                'products' => $products
            ]);
        } catch (Exception $e) {
            Response::error('Error al obtener productos: ' . $e->getMessage(), 500);
        }
    }

    // ==========================================
    // CREAR UN PRODUCTO
    // ==========================================
    public function createProduct()
    {
        AuthMiddleware::handle();

        $data = Response::json();

        $required = ['id_category', 'name', 'description', 'price'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                Response::error("El campo $field es obligatorio", 400);
                return;
            }
        }

        // Generar el slug automáticamente
        $slug = $this->generateSlug($data['name']);

        $clearData = [
            'id_category'   => (int) $data['id_category'],
            'code_product' => !empty($data['code_product']) ? $data['code_product'] : $this->generateCode($data['id_category']),
            'name' => htmlspecialchars(trim(strip_tags($data['name']))),
            'description' => htmlspecialchars(trim(strip_tags($data['description']))),
            'slug' => $slug,
            'price' => preg_replace('/[^0-9.]/', '', $data['price']),
        ];

        try {
            $productId = $this->model->insertProduct($clearData);
            Response::success([
                'message'    => 'Producto creado con éxito',
                'product_id' => $productId
            ]);
        } catch (Exception $e) {
            Response::error('Error al crear el producto: ' . $e->getMessage(), 500);
        }
    }

    // ==========================================
    // ACTUALIZAR UN PRODUCTO
    // ==========================================
    public function updateProduct($id)
    {
        AuthMiddleware::handle();

        if (!$id) {
            Response::error('ID del producto no proporcionado', 400);
            return;
        }

        $data = Response::json();

        $product = $this->model->getProductById($id);
        if (!$product) {
            Response::error("No se ha encontrado ningún producto con el id {$id}", 400);
            return;
        }

        $required = ['id_category', 'name', 'description', 'price'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                Response::error("El campo $field es obligatorio", 400);
                return;
            }
        }

        // Si no se pasa el 'code_product' en los datos, entonces generamos el código
        $code_product = !empty($data['code_product']) ? $data['code_product'] : $product['code_product'];

        // Generar el slug automáticamente
        $slug = $this->generateSlug($data['name']);

        // Si no se pasa una nueva imagen, conservamos la imagen actual
        $image = !empty($data['image']) ? $data['image'] : $product['image'];

        $clearData = [
            'id_category'   => (int) $data['id_category'],
            'name' => htmlspecialchars(trim(strip_tags($data['name']))),
            'description' => htmlspecialchars(trim(strip_tags($data['description']))),
            'id_state' => htmlspecialchars(trim(strip_tags($data['id_state']))),
            'price' => preg_replace('/[^0-9]/', '', $data['price']),
            'slug' => $slug, // Incluir el slug en la actualización
            'code_product' => $code_product, // Usar el código existente o uno nuevo
            'image' => $image, // Usar la imagen existente o la nueva
        ];

        try {
            $result = $this->model->updateProduct($id, $clearData);

            if ($result) {
                Response::success(['message' => 'Producto actualizado correctamente']);
            } else {
                Response::error('No se pudo actualizar el producto', 500);
            }
        } catch (Exception $e) {
            Response::error('Error al actualizar el producto: ' . $e->getMessage(), 500);
        }
    }


    // ==========================================
    // ELIMINAR UN PRODUCTO
    // ==========================================
    public function deleteProduct($id)
    {
        AuthMiddleware::handle();

        if (!$id) {
            Response::error('ID del producto no proporcionado', 400);
            return;
        }

        try {
            $result = $this->model->deleteProduct($id);

            if ($result) {
                Response::success(['message' => 'Producto eliminado correctamente']);
            } else {
                Response::error('No se pudo eliminar el producto', 500);
            }
        } catch (Exception $e) {
            Response::error('Error al eliminar el producto: ' . $e->getMessage(), 500);
        }
    }

    // ==========================================
    // OBTENER PRODUCTOS POR FILTROS
    // ==========================================
    public function getProductsByFilters()
    {
        AuthMiddleware::handle();

        $filters = [];

        if (!empty($_GET['name'])) {
            $filters['name'] = trim($_GET['name']);
        }

        if (!empty($_GET['category'])) {
            $filters['category'] = trim($_GET['category']);
        }

        try {
            $products = $this->model->filterProduct($filters);

            Response::success([
                'message' => 'Productos filtrados obtenidos',
                'products' => $products
            ]);
        } catch (Exception $e) {
            Response::error('Error al filtrar productos: ' . $e->getMessage(), 500);
        }
    }

    // ==========================================
    // GENERAR CÓDIGO DE PRODUCTO
    // ==========================================
    private function generateCode($id_category)
    {
        $categoryName = $this->model->getCategoryNameById($id_category);

        if (!$categoryName) {
            $prefix = 'PR';
        } else {
            $words = explode(' ', strtoupper($categoryName));
            $prefix = '';

            foreach ($words as $word) {
                $prefix .= $word[0];
            }

            $prefix = substr($prefix, 0, 2);
        }

        do {
            $randomNumber = mt_rand(100, 999);
            $randomLetters = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2));
            $code = $prefix . '-' . $randomNumber . $randomLetters;
        } while ($this->model->isCodeProductExists($code));

        return $code;
    }

    // ==========================================
    // VERIFICA SI EL PRODUCTO EXISTE
    // ==========================================
    private function checkProductExists($id)
    {
        $product = $this->model->getProductById($id);
        if (!$product) {
            Response::error("No se ha encontrado ningún producto con el id {$id}", 400);
            return false;
        }
        return true;
    }

    // ==========================================
    // GENERAR SLUG
    // ==========================================
    private function generateSlug($name)
    {
        // Convertir a minúsculas
        $slug = strtolower($name);

        // Reemplazar los espacios por guiones
        $slug = str_replace(' ', '-', $slug);

        // Eliminar caracteres especiales
        $slug = preg_replace('/[^a-z0-9-]/', '', $slug);

        return $slug;
    }
}
