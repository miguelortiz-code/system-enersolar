<?php

require_once 'models/global.connection.php';

class userModel {

    /* ========================================================== 
                OBTENER TODOS LOS USUARIOS
    ==========================================================*/
    public function getAllUsers() {
        try {
            $pdo = ConnectionDB(); 
            $stmt = $pdo->prepare("SELECT * FROM users");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Error al obtener todos los productos: ' . $e->getMessage());
        }
    }
}
