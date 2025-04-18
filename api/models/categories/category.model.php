<?php 

require_once 'config/connection.php';
class categoryModel{
    private $pdo;

    public function __construct(){
        $this->pdo = Connection::getConnection();
    }


    public function getAllCategories(){
        try{
            $stmt = $this->pdo->prepare("SELECT * FROM categories");
            $stmt->execute();
            return  $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            throw new Exception('Error al obtener todas las categorias ' . $e->getMessage());
        }
    }
}