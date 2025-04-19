<?php

require_once 'models/global.connection.php';
class productModel
{
    /* ========================================================== 
                OBTENER TODOS LOS PRODUCTOS
    ==========================================================*/
    public function getAllProducts()
    {
        try {
            $pdo = ConnectionDB();
            $stmt = $pdo->prepare("SELECT * FROM products");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Error al obtener todos los productos ' . $e->getMessage());
        }
    }

    /* ========================================================== 
                OBTENER UN PRODUCTO
    ==========================================================*/
    public function getProduct($id)
    {
        try {
            $pdo = ConnectionDB();
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id_product = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Error al obtener el producto ' . $e->getMessage());
        }
    }


    /* ========================================================== 
            INSERTAR UN PRODUCTO
    ==========================================================*/
    public function insertProduct($data)
    {
        try {
            $pdo = ConnectionDB();
            $stmt = $pdo->prepare("INSERT INTO products (code_product, name,  description, image, slug, stock, price, id_category, id_state) 
            VALUES (:code, :name, :description, :image, :slug, :stock, :price, :id_category, :id_state)");
            $stmt->execute([
                ':code' => $data['code_product'],
                ':name' => $data['name'],
                ':description' => $data['description'],
                ':image' => $data['image'],
                ':slug' => $data['slug'],
                ':stock' => $data['stock'],
                ':price' => $data['price'],
                ':id_category' => $data['id_category'],
                ':id_state' => $data['id_state'],
            ]);
            return $stmt = $pdo->lastInsertId();
        } catch (Exception $e) {
            throw new Exception('Error al insertar el producto ' . $e->getMessage());
        }
    }


    /* ========================================================== 
            EDITAR UN PRODUCTO
    ==========================================================*/
    public function updateProduct($id, $data){
        try{
            $pdo = ConnectionDB();
            $stmt = $pdo->prepare("UPDATE products SET
                code_product = :code,
                name = :name,
                description = :description,
                image = :image,
                slug = :slug,
                stock = :stock,
                price = :price,
                id_category = :id_category,
                updated_date =  NOW(),
                id_state = :id_state
                WHERE id_product = :id");

            $stmt->execute([
                ':code' => $data['code_product'],
                ':name' => $data['name'],
                ':description' => $data['description'],
                ':image' => $data['image'],
                ':slug' => $data['slug'],
                ':stock' => $data['stock'],
                ':price' => $data['price'],
                ':id_category' => $data['id_category'],
                ':id_state' => $data['id_state'],
                ':id' => $id
            ]);

            return $stmt->rowCount();
        }catch(Exception $e){
            throw new Exception('Error al editar el producto ' . $e->getMessage());
        }
    }

    /* ========================================================== 
            ELIMINAR UN PRODUCTO
    ==========================================================*/
    public function deleteProduct($id){
        try{
            $pdo = ConnectionDB();
            $stmt =  $pdo->prepare("DELETE FROM products WHERE id_product = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->rowCount();
        }catch(Exception $e){
            throw new Exception('Error al eliminar el producto ' . $e->getMessage());
        }
    }
}