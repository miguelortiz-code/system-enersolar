<?php
require_once 'models/products/product.model.php';

class ProductController
{

    /* ========================================================== 
                OBTENER TODOS LOS PRODUCTOS
    ==========================================================*/
    public function getAllProducts(){
        try {
            $productModel = new ProductModel();
            $products = $productModel->getAllProducts();

            if ($products) {
                echo json_encode([
                    'success' => true,
                    'total' => count($products),
                    'data' => $products
                ]);
            } else {
                echo json_encode([
                    'error' => true,
                    'message' => 'Productos no encontrados'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => true, 'message' => 'Error al obtener productos: ' . $e->getMessage()]);
        }
    }

    /* ========================================================== 
                OBTENER PRODUCTO POR ID
    ==========================================================*/
    public function getProductById($id){
        try {
            $productModel = new ProductModel();
            $product = $productModel->getProduct($id);

            if ($product) {
                echo json_encode(['success' => true, 'data' => $product]);
            } else {
                echo json_encode(['error' => true, 'message' => 'Producto no encontrado']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => true, 'message' => 'Error al obtener el producto: ' . $e->getMessage()]);
        }
    }

    /* ========================================================== 
                CREAR PRODUCTO
    ==========================================================*/
    public function createdProduct(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data  = $this->json();

            if (!isset(
                $data['code_product'],
                $data['name'],
                $data['description'],
                $data['image'],
                $data['slug'],
                $data['stock'],
                $data['price'],
                $data['id_category'],
                $data['id_state']
            )) {
                http_response_code(400);
                echo json_encode(['error' => true, 'message' => 'Todos los campos son obligatorios.']);
                return;
            }

            try {
                $productModel = new ProductModel();
                $lastInsertId = $productModel->insertProduct($data);
                echo json_encode([
                    'success' => true,
                    'message' => 'Producto creado correctamente',
                    'id_product' => $lastInsertId
                ]);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['error' => true, 'message' => 'Error al crear el producto: ' . $e->getMessage()]);
            }
        }
    }

    /* ========================================================== 
                EDITAR PRODUCTO
    ==========================================================*/
    public function updateProduct($params){
        $id = $params['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID inválido']);
            return;
        }

        $data = $this->json();

        if (!$data || !isset(
            $data['name'],
            $data['description'],
            $data['image'],
            $data['slug'],
            $data['stock'],
            $data['price'],
            $data['id_category'],
            $data['id_state']
        )) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            return;
        }

        try {
            $productModel = new ProductModel();
            $update = $productModel->updateProduct((int)$id, $data);

            if ($update > 0) {
                echo json_encode(['success' => true, 'message' => 'Producto actualizado correctamente']);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Producto no encontrado o sin cambios']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => true, 'message' => 'Error al actualizar el producto: ' . $e->getMessage()]);
        }
    }

    /* ========================================================== 
                ELIMINAR PRODUCTO
    ==========================================================*/
    public function deleteProduct($params){
        $id = $params['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID inválido']);
            return;
        }

        try {
            $productModel = new ProductModel();
            $deleted = $productModel->deleteProduct((int)$id);

            if ($deleted > 0) {
                echo json_encode(['success' => true, 'message' => 'Producto eliminado correctamente']);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Producto no encontrado o ya fue eliminado']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => true, 'message' => 'Error al eliminar el producto: ' . $e->getMessage()]);
        }
    }

    /* ========================================================== 
                PARSEAR JSON
    ==========================================================*/
    private function json()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}