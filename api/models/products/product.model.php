<?php

require_once 'config/connection.php';
class productModel{
    private $pdo;
    /* ========================================================== 
                OBTENER CONEXION A LA BASE DE DATOS
    ==========================================================*/
    public function __construct(){
        $this->pdo = Connection::getConnection();
    }

    /* ========================================================== 
                OBTENER TODOS LOS PRODUCTOS
    ==========================================================*/
    public function getAllProducts(){
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM products");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Error al obtener todos los productos ' . $e->getMessage());
        }
    }
}