<?php

require_once 'config/connection.php';

class ProductModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Connection::getConnection();
    }

    public function getAllProducts()
    {
        $query = "SELECT p.code_product, p.name, p.description, p.image, p.slug, p.price, c.category, s.state 
                  FROM products p
                  JOIN categories c ON p.id_category = c.id_category
                  JOIN states s ON p.id_state = s.id_state";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Error al obtener todos los productos: ' . $e->getMessage());
        }
    }

    public function insertProduct($data)
    {
        try {
            // Verificar si la categoría existe
            $checkQuery = "SELECT id_category FROM categories WHERE id_category = :id_category";
            $checkStmt = $this->pdo->prepare($checkQuery);
            $checkStmt->bindValue(':id_category', $data['id_category']);
            $checkStmt->execute();
    
            // Si no existe la categoría, lanzar una excepción
            if ($checkStmt->rowCount() === 0) {
                throw new Exception('La categoría especificada no existe.');
            }
    
            // Preparar la consulta de inserción
            $query = "INSERT INTO products (code_product, name, description, slug, price, id_category)
                      VALUES (:code_product, :name, :description, :slug, :price, :id_category)";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':id_category', $data['id_category']);
            $stmt->bindValue(':code_product', $data['code_product']);
            $stmt->bindValue(':name', $data['name']);
            $stmt->bindValue(':description', $data['description']);
            $stmt->bindValue(':slug', $data['slug']);
            $stmt->bindValue(':price', $data['price']);
            
            // Ejecutar la consulta
            $stmt->execute();
    
            // Retornar el ID del último producto insertado
            return $this->pdo->lastInsertId();
            
        } catch (Exception $e) {
            // Lanza una excepción si algo falla
            throw new Exception('Error en el proceso de inserción: ' . $e->getMessage());
        }
    }

    

    public function getProductById($id)
    {
        $query = "SELECT p.code_product, p.name, p.description, p.image, p.slug, p.price, c.category, s.state
                  FROM products p
                  JOIN categories c ON p.id_category = c.id_category
                  JOIN states s ON p.id_state = s.id_state
                  WHERE p.id_product = :id_product";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':id_product', $id, PDO::PARAM_INT);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Verificamos si el producto fue encontrado
            if (!$product) {
                // Aquí no lanzamos una excepción, simplemente retornamos false o null
                return false;
            }
    
            return $product;
        } catch (Exception $e) {
            // Manejamos las excepciones de la base de datos
            throw new Exception('Error al obtener el producto: ' . $e->getMessage());
        }
    }

    

    public function updateProduct($id, $data)
    {
        try {
            $checkQuery = "SELECT id_product FROM products WHERE id_product = :id_product";
            $checkStmt = $this->pdo->prepare($checkQuery);
            $checkStmt->bindValue(':id_product', $id);
            $checkStmt->execute();

            if ($checkStmt->rowCount() === 0) {
                throw new Exception('El producto a actualizar no existe.');
            }

            $query = "UPDATE products 
                      SET id_category = :id_category, code_product = :code_product, name = :name, description = :description, image = :image, slug = :slug, price = :price, id_state = :id_state
                      WHERE id_product = :id_product";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':id_category', $data['id_category']);
            $stmt->bindValue(':code_product', $data['code_product']);
            $stmt->bindValue(':name', $data['name']);
            $stmt->bindValue(':description', $data['description']);
            $stmt->bindValue(':image', $data['image']);
            $stmt->bindValue(':slug', $data['slug']);
            $stmt->bindValue(':price', $data['price']);
            $stmt->bindValue(':id_state', $data['id_state']);
            $stmt->bindValue(':id_product', $id, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            throw new Exception('Error al actualizar producto: ' . $e->getMessage());
        }
    }

    public function deleteProduct($id)
    {
        try {
            // Primero, verificar si el producto existe y traer la imagen
            $checkQuery = "SELECT id_product, image FROM products WHERE id_product = :id_product";
            $checkStmt = $this->pdo->prepare($checkQuery);
            $checkStmt->bindValue(':id_product', $id, PDO::PARAM_INT);
            $checkStmt->execute();
    
            if ($checkStmt->rowCount() === 0) {
                throw new Exception('El producto a eliminar no existe.');
            }
    
            $product = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
            // Si el producto tiene una imagen, eliminar el archivo del servidor
            if (!empty($product['image'])) {
                $imagePath = __DIR__ . '/../../frontend/public/uploads/productos/' . $product['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
    
            // Ahora sí, eliminar el producto de la base de datos
            $query = "DELETE FROM products WHERE id_product = :id_product";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':id_product', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            return true;
        } catch (Exception $e) {
            throw new Exception('Error al eliminar producto: ' . $e->getMessage());
        }
    }
    

    public function filterProduct($filters)
    {
        try {
            $query = "SELECT p.code_product, p.name, p.description, p.image, p.slug, p.price, c.category, s.state
                      FROM products p
                      JOIN categories c ON p.id_category = c.id_category
                      JOIN states s ON p.id_state = s.id_state
                      WHERE 1=1";

            $params = [];

            if (!empty($filters['name'])) {
                $query .= " AND p.name LIKE :name";
                $params[':name'] = '%' . $filters['name'] . '%';
            }

            if (!empty($filters['category'])) {
                $query .= " AND c.category LIKE :category";
                $params[':category'] = '%' . $filters['category'] . '%';
            }

            if (!empty($filters['price_min'])) {
                $query .= " AND p.price >= :price_min";
                $params[':price_min'] = $filters['price_min'];
            }

            if (!empty($filters['price_max'])) {
                $query .= " AND p.price <= :price_max";
                $params[':price_max'] = $filters['price_max'];
            }

            if (!empty($filters['code_product'])) {
                $query .= " AND p.code_product <= :code_product";
                $params[':code_product'] = $filters['code_product'];
            }

            $stmt = $this->pdo->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Error al filtrar productos: ' . $e->getMessage());
        }
    }

    public function getCategoryNameById($id)
    {
        $query = "SELECT category FROM categories WHERE id_category = :id";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? $row['category'] : null;
        } catch (Exception $e) {
            throw new Exception('Error al obtener el nombre de la categoria: ' . $e->getMessage());
        }
    }

    public function isCodeProductExists($code)
    {
        $query = "SELECT COUNT(*) FROM products WHERE code_product = :code";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':code', $code);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            throw new Exception('Error al verificar el código del producto: ' . $e->getMessage());
        }
    }
}