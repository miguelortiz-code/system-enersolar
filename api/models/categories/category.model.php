<?php 

require_once 'models/global.connection.php';
class categoryModel{
    public function getAllCategories(){
        try{
            $pdo = ConnectionDB();
            $stmt = $pdo->prepare("SELECT * FROM categories");
            $stmt->execute();
            return  $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            throw new Exception('Error al obtener todas las categorias ' . $e->getMessage());
        }
    }
}